<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $name
 * @property string|null $email        // В БД хранится нормализованным (lower)
 * @property string|null $phone        // В БД хранится в виде +7XXXXXXXXXX
 * @property string|null $comment      // Заметки менеджера
 * @property string|null $client_wishes // Пожелания клиента с формы сайта (readonly в админке)
 */
class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'comment',        // заметки менеджера
        'client_wishes',  // пожелания клиента (заполняется только из формы сайта)
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** Хотим, чтобы виртуальный атрибут всегда был доступен через $client->toArray() */
    protected $appends = [
        'phone_pretty',
    ];

    /* =======================
     |  Связи
     |=======================*/
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /* =======================
     |  Мутаторы (централизованная нормализация)
     |=======================*/

    /** Приводит email к каноничному виду при каждом присвоении */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = self::normalizeEmail($value);
    }

    /** Приводит телефон к виду +7XXXXXXXXXX при каждом присвоении */
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = self::normalizePhone($value);
    }

    /* =======================
     |  Скоупы (удобный поиск)
     |=======================*/

    /**
     * Поиск по имени / email / телефону (в т.ч. если телефон введён в произвольном формате).
     */
    public function scopeSearchText($query, ?string $q)
    {
        $q = trim((string) $q);
        if ($q === '') {
            return $query;
        }

        // Извлекаем цифры из поискового запроса
        $digits = preg_replace('/\D+/', '', $q);
        
        return $query->where(function ($qq) use ($q, $digits) {
            // Поиск по имени
            $qq->where('name', 'like', "%{$q}%")
               // Поиск по email
               ->orWhere('email', 'like', "%{$q}%");
            
            // Если есть цифры в запросе, ищем по телефону
            if ($digits !== '') {
                // Поиск по частичным цифрам телефона (без +7)
                $qq->orWhere('phone', 'like', "%{$digits}%");
                
                // Если это похоже на полный телефон — нормализуем и ищем точно
                $phone = self::normalizePhone($q);
                if ($phone) {
                    $qq->orWhere('phone', $phone);
                }
            }
        });
    }

    /* =======================
     |  Утилиты нормализации
     |=======================*/

    /** Нормализуем email (trim + lower + базовая валидация) */
    public static function normalizeEmail(?string $email): ?string
    {
        if ($email === null) {
            return null;
        }
        $email = trim(mb_strtolower($email));
        if ($email === '') {
            return null;
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    /**
     * Нормализуем российский телефон -> +7XXXXXXXXXX.
     * Принимаем:
     *  - 10 цифр (добавим +7);
     *  - 11 цифр, начинающихся на 7 или 8 (8 заменяем на 7).
     */
    public static function normalizePhone(?string $input): ?string
    {
        if ($input === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $input) ?? '';
        if ($digits === '') {
            return null;
        }

        if (strlen($digits) === 10) {
            return '+7' . $digits;
        }

        if (strlen($digits) === 11) {
            if ($digits[0] === '8') {
                $digits[0] = '7';
            }
            if ($digits[0] === '7') {
                return '+7' . substr($digits, 1);
            }
        }

        return null;
    }

    /** Красивый вид телефона из нормализованного */
    public static function prettyPhone(?string $normalized): ?string
    {
        if (!$normalized) {
            return null;
        }
        if (preg_match('/^\+7(\d{10})$/', $normalized, $m)) {
            $n = $m[1];
            return sprintf(
                '+7 (%s) %s-%s-%s',
                substr($n, 0, 3),
                substr($n, 3, 3),
                substr($n, 6, 2),
                substr($n, 8, 2)
            );
        }
        return $normalized;
    }

    /* =======================
     |  Аксессоры
     |=======================*/

    public function getPhonePrettyAttribute(): ?string
    {
        return self::prettyPhone($this->phone);
    }

}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\CalendarService;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',        // историческое поле (может остаться пустым при бандлах)
        'bundle_id',         // НОВОЕ: ссылка на бандл (опционально, если выбор через даты+ночей)
        'booking_date',
        'booking_end_date',  // дата окончания бронирования (обязательное поле)
        'nights',            // количество ночей (рассчитывается автоматически из дат)
        'people_count',
        'status',
        'comment',           // комментарий менеджера (редактируемое)
        'client_wishes',     // пожелания клиента с сайта (read-only в админке)
        'manager_comment',   // комментарий менеджера (альтернативное название)
        'total_price',       // кеш суммы заявки
    ];

    protected $casts = [
        'booking_date'     => 'date',
        'booking_end_date' => 'date',
        'nights'           => 'integer',
    ];

    // Статусы заявки
    public const STATUS_NEW       = 'new';
    public const STATUS_PAID      = 'paid';       // «Оплачена»
    public const STATUS_COMPLETED = 'completed';  // «Завершена»
    public const STATUS_CANCELLED = 'cancelled';  // «Отмена»

    public const STATUSES = [
        self::STATUS_NEW       => 'Новая',
        self::STATUS_PAID      => 'Оплачена',
        self::STATUS_COMPLETED => 'Завершена',
        self::STATUS_CANCELLED => 'Отмена',
    ];

    /* =======================
     |  Связи
     |=======================*/
    public function client()  { return $this->belongsTo(Client::class); }
    public function service() { return $this->belongsTo(Service::class); }
    public function bundle()  { return $this->belongsTo(Bundle::class); }
    public function order()   { return $this->hasOne(Order::class); }

    /** Основные услуги, выбранные в заявке. */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'application_service', 'application_id', 'service_id')
            ->withTimestamps();
    }

    /** Доп. услуги, выбранные в заявке, с количествами. */
    public function addons()
    {
        return $this->belongsToMany(Option::class, 'application_addon', 'application_id', 'option_id')
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    /* =======================
     |  Виртуальные поля/хелперы
     |=======================*/

    /** Количество ночей для отображения: поле nights содержит дни, поэтому ночи = дни - 1. */
    public function getNightsCountAttribute(): int
    {
        $n = (int) ($this->nights ?? 0);
        if ($n >= 2) {
            return $n - 1; // Дни минус 1 = ночи
        }
        // Фоллбек на старую модель с датами (на всякий случай)
        $from = $this->booking_date;
        $to   = $this->booking_end_date ?? $this->booking_date;
        if (!$from) {
            return 1;
        }
        return max(1, $from->diffInDays($to));
    }

    /** Количество дней для отображения в таблице. */
    public function getDaysCountAttribute(): int
    {
        // Поле nights уже содержит количество дней (не ночей!)
        $n = (int) ($this->nights ?? 0);
        if ($n >= 1) {
            return $n;
        }
        
        // Фоллбек на расчет из дат
        $from = $this->booking_date;
        $to   = $this->booking_end_date ?? $this->booking_date;
        if (!$from) {
            return 1;
        }
        
        // diffInDays возвращает количество дней между датами
        // С 24 по 27 = 3 дня разницы, но нам нужно 4 дня (включительно)
        return max(1, $from->diffInDays($to) + 1);
    }

    /** Рассчитанная сумма заявки (bundle + addons). */
    public function getTotalPriceCalcAttribute(): int
    {
        return $this->calculateTotal();
    }

    /* =======================
     |  Автосоздание заказа при статусе "Оплачена"
     |=======================*/
    protected static function booted(): void
    {
        // Автоматический расчет количества ночей из дат
        static::saving(function (Application $app) {
            if ($app->booking_date && $app->booking_end_date) {
                $start = Carbon::parse($app->booking_date);
                $end = Carbon::parse($app->booking_end_date);
                $days = $start->diffInDays($end);
                
                // Валидация диапазона дат
                if ($days < 2) {
                    throw ValidationException::withMessages([
                        'booking_end_date' => 'Минимум 3 дня (2 ночи). Выбранный диапазон слишком короткий.',
                    ]);
                }
                if ($days > 9) {
                    throw ValidationException::withMessages([
                        'booking_end_date' => 'Максимум 10 дней (9 ночей). Выбранный диапазон слишком длинный.',
                    ]);
                }
                
                // Проверка пересечений с уже забронированными датами
                $calendarService = app(CalendarService::class);
                $conflictCheck = $calendarService->checkDateRangeConflict(
                    $app->booking_date->format('Y-m-d'),
                    $app->booking_end_date->format('Y-m-d'),
                    $app->id // Исключаем текущую заявку при редактировании
                );
                
                if ($conflictCheck['has_conflicts']) {
                    $conflictMessages = [];
                    foreach ($conflictCheck['conflicts'] as $conflict) {
                        if ($conflict['type'] === 'blocked') {
                            $conflictMessages[] = "Дата {$conflict['date']} заблокирована: {$conflict['reason']}";
                        } elseif ($conflict['type'] === 'application') {
                            $conflictMessages[] = "Пересечение с заявкой #{$conflict['id']} ({$conflict['client_name']}) на период {$conflict['start_date']} - {$conflict['end_date']}";
                        } elseif ($conflict['type'] === 'order') {
                            $conflictMessages[] = "Пересечение с заказом {$conflict['number']} ({$conflict['client_name']}) на период {$conflict['start_date']} - {$conflict['end_date']}";
                        }
                    }
                    
                    throw ValidationException::withMessages([
                        'booking_date' => 'Выбранный период пересекается с уже забронированными датами:',
                        'booking_end_date' => implode('; ', $conflictMessages),
                    ]);
                }
                
                $app->nights = max(1, $days + 1);
            }
        });

        // Обновление существующей заявки
        static::updating(function (Application $app) {
            if ($app->isDirty('status') && $app->status === self::STATUS_PAID) {
                $app->createOrderIfNeeded();
            }
        });

        // Синхронизация статусов с заказом
        static::saved(function (Application $app) {
            if ($app->wasChanged('status') && $app->order) {
                $app->syncStatusWithOrder();
            }
        });


        // Создание новой заявки уже со статусом paid
        static::saved(function (Application $app) {
            if ($app->status === self::STATUS_PAID) {
                $app->createOrderIfNeeded();
            }
        });
    }

    /** Единый калькулятор суммы: bundle + addons. */
    public function calculateTotal(): int
    {
        $total = 0;

        // Цена формата отдыха (если выбран)
        if ($this->bundle_id && ($bundle = $this->bundle)) {
            $total += (int) $bundle->price;
        }

        // Доп. услуги из pivot
        $people = max(1, (int) $this->people_count);
        $nights = max(1, (int) $this->nights);

        $this->loadMissing('addons');
        foreach ($this->addons as $addon) {
            $qty   = max(1, (int) ($addon->pivot->quantity ?? 1));
            $price = (int) $addon->price;

            // Базовая цена опции
            $lineTotal = $price * $qty;
            
            // Применяем множители
            if ($addon->price_per_person) {
                $lineTotal *= $people;
            }
            if ($addon->price_per_day) {
                $lineTotal *= $nights;
            }
            
            $total += $lineTotal;
        }

        return $total;
    }

    /** Создать заказ, если его ещё нет, с проверками и снапшотами бандла/доп.услуг. */
    private function createOrderIfNeeded(): void
    {
        if ($this->order()->exists()) {
            return;
        }

        // Формат отдыха обязателен в новой логике (через выбор bundle_id или nights→bundle)
        if (! $this->bundle_id) {
            throw ValidationException::withMessages([
                'bundle_id' => 'Нельзя оформить заказ: бандл не выбран.',
            ]);
        }
        if (! $this->booking_date) {
            throw ValidationException::withMessages([
                'booking_date' => 'Нельзя оформить заказ: не указана дата заезда.',
            ]);
        }
        if ((int) $this->nights < 1) {
            throw ValidationException::withMessages([
                'nights' => 'Нельзя оформить заказ: количество ночей должно быть ≥ 1.',
            ]);
        }
        if ((int) $this->people_count < 1) {
            throw ValidationException::withMessages([
                'people_count' => 'Нельзя оформить заказ: количество людей должно быть ≥ 1.',
            ]);
        }

        $bundle = Bundle::find($this->bundle_id);
        if (! $bundle) {
            throw ValidationException::withMessages([
                'bundle_id' => 'Нельзя оформить заказ: бандл не найден.',
            ]);
        }

        DB::transaction(function () use ($bundle) {
            $people = max(1, (int) $this->people_count);

            // Снимок основных услуг заявки (для печати/истории)
            $bundleServices = $this->services()
                ->get(['services.id', 'services.name'])
                ->map(fn ($s) => ['id' => (int) $s->id, 'name' => (string) $s->name])
                ->values()
                ->all();

            // Снимок дополнительных услуг заявки (для печати/истории)
            $addons = $this->addons()
                ->get(['options.id', 'options.name'])
                ->map(fn ($a) => ['id' => (int) $a->id, 'name' => (string) $a->name])
                ->values()
                ->all();

            // Итог по заявке (bundle + addons)
            $total = $this->calculateTotal();

            $order = new Order([
                'application_id'           => $this->id,
                'client_id'                => $this->client_id,
                'service_id'               => $this->service_id, // может быть null для бандлов
                'bundle_id'                => $bundle->id,

                'number'                   => Order::nextNumber(),

                // Снапшоты бандла
                'bundle_name_snapshot'     => (string) $bundle->name,
                'bundle_nights_snapshot'   => (int) $this->nights,
                'bundle_price_snapshot'    => (int) $bundle->price,
                'bundle_services_snapshot' => !empty($bundleServices) ? json_encode($bundleServices, JSON_UNESCAPED_UNICODE) : null,

                // Снапшоты клиента (как раньше)
                'service_name_snapshot'    => $bundle->name, // название формата отдыха вместо услуги
                'price_snapshot'           => 0,
                'price_type_snapshot'      => 'per_group',

                'client_name_snapshot'     => (string) ($this->client->name ?? ''),
                'client_email_snapshot'    => (string) ($this->client->email ?? ''),
                'client_phone_snapshot'    => (string) ($this->client->phone_pretty ?? ''),
                'client_comment'           => (string) ($this->client_wishes ?? ''),

                'addons_snapshot'          => !empty($addons) ? json_encode($addons, JSON_UNESCAPED_UNICODE) : null,

                'booking_date'             => $this->booking_date,
                'booking_end_date'         => $this->booking_end_date,
                'people_count'             => $people,

                'total_price'              => $total, // историческое поле
                'discount_amount'          => 0,
                'final_total'              => $total,

                'status'                   => Order::STATUS_ACTIVE,
                'comment'                  => $this->comment,
            ]);

            $order->save();

            // Снапшоты доп. услуг
            $this->loadMissing('addons');
            foreach ($this->addons as $addon) {
                $qty       = max(1, (int) ($addon->pivot->quantity ?? 1));
                $price     = (int) $addon->price;
                $lineTotal = $price * $qty;

                $order->addons()->create([
                    'name'       => (string) $addon->name,
                    'price'      => $price,
                    'price_type' => 'per_group', // Для options всегда за группу
                    'quantity'   => $qty,
                    'total'      => $lineTotal,
                ]);
            }
        });
    }

    /**
     * Синхронизация статуса заявки со статусом заказа
     */
    public function syncStatusWithOrder(): void
    {
        if (!$this->order) {
            return;
        }

        $orderStatus = $this->order->status;
        $newApplicationStatus = null;

        switch ($orderStatus) {
            case Order::STATUS_ACTIVE:
                $newApplicationStatus = self::STATUS_PAID;
                break;
            case Order::STATUS_COMPLETED:
                $newApplicationStatus = self::STATUS_COMPLETED;
                break;
            case Order::STATUS_CANCELLED:
                $newApplicationStatus = self::STATUS_CANCELLED;
                break;
        }

        if ($newApplicationStatus && $this->status !== $newApplicationStatus) {
            $this->update(['status' => $newApplicationStatus]);
        }
    }
}
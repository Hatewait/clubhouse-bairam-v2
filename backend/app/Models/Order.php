<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE    = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /** Для бэджей/селектов в Filament */
    public const STATUSES = [
        self::STATUS_ACTIVE    => 'Активен',
        self::STATUS_COMPLETED => 'Завершён',
        self::STATUS_CANCELLED => 'Отменён',
    ];

    protected $fillable = [
        'application_id',
        'client_id',
        'service_id',
        'number',

        'service_name_snapshot',
        'price_snapshot',
        'price_type_snapshot',

        'bundle_name_snapshot',
        'bundle_nights_snapshot',
        'bundle_price_snapshot',
        'bundle_services_snapshot',

        'client_name_snapshot',
        'client_email_snapshot',
        'client_phone_snapshot',
        'client_comment',

        'addons_snapshot',

        'booking_date',
        'booking_end_date',
        'people_count',

        'total_price',
        'discount_amount',
        'final_total',

        'status',
        'comment',
    ];

    protected $casts = [
        'booking_date'     => 'date',
        'booking_end_date' => 'date',
        'total_price'      => 'integer',
        'discount_amount'  => 'integer',
        'final_total'      => 'integer',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /* ========= Связи ========= */
    public function application() { return $this->belongsTo(Application::class); }
    public function client()      { return $this->belongsTo(Client::class); }
    public function service()     { return $this->belongsTo(Service::class); }
    public function addons()      { return $this->hasMany(OrderAddon::class); }

    /* ========= Иммутабельность: можно править статус и комментарий ========= */
    protected static function booted(): void
    {
        static::updating(function (Order $order) {
            $dirty   = array_keys($order->getDirty());
            $allowed = ['status', 'comment', 'updated_at'];

            $illegal = array_diff($dirty, $allowed);
            if (! empty($illegal)) {
                throw ValidationException::withMessages([
                    'order' => 'Заказ менять нельзя. Разрешено редактировать только статус и комментарий.',
                ]);
            }
        });

        // Синхронизация статуса заказа с заявкой
        static::saved(function (Order $order) {
            if ($order->wasChanged('status') && $order->application) {
                $order->syncStatusWithApplication();
            }
        });

        // При удалении заказа возвращаем заявку в статус "новая"
        static::deleting(function (Order $order) {
            if ($order->application) {
                $order->application->update(['status' => Application::STATUS_NEW]);
            }
        });
    }

    /* ========= Утилиты ========= */
    public static function nextNumber(): string
    {
        $year   = now()->format('Y');
        $prefix = "ORD-{$year}-";

        $last = static::where('number', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('number');

        $seq = 1;
        if ($last && preg_match('/^ORD-\d{4}-(\d{4,})$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }
        return $prefix . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    public function getNumberPrettyAttribute(): string
    {
        return $this->number;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getNightsCountAttribute(): int
    {
        $from = $this->booking_date;
        $to   = $this->booking_end_date ?? $from;
        if (! $from) {
            return 1;
        }
        return max(1, Carbon::parse($from)->diffInDays(Carbon::parse($to)) + 1);
    }

    /**
     * Синхронизация статуса заказа со статусом заявки
     */
    public function syncStatusWithApplication(): void
    {
        if (!$this->application) {
            return;
        }

        $newApplicationStatus = null;

        switch ($this->status) {
            case self::STATUS_ACTIVE:
                $newApplicationStatus = Application::STATUS_PAID;
                break;
            case self::STATUS_COMPLETED:
                $newApplicationStatus = Application::STATUS_COMPLETED;
                break;
            case self::STATUS_CANCELLED:
                $newApplicationStatus = Application::STATUS_CANCELLED;
                break;
        }

        if ($newApplicationStatus && $this->application->status !== $newApplicationStatus) {
            $this->application->update(['status' => $newApplicationStatus]);
        }
    }
}
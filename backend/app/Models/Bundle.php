<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Bundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'show_price_on_site', // показывать цену на сайте
        'is_active',
        'description',
        'gallery',           // галерея изображений
        'site_title',        // заголовок для сайта
        'site_subtitle',     // подзаголовок для сайта
        'site_description',  // описание для сайта
        'site_image_path',   // картинка для сайта
    ];

    protected $casts = [
        'price'             => 'integer',
        'show_price_on_site' => 'boolean',
        'is_active'         => 'boolean',
        'gallery'           => 'array',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /* =======================
     |  Связи
     |=======================*/

    /**
     * Основные услуги, входящие в пакет (информативно).
     * В интерфейсе/валидации следим, чтобы сюда попадали только services.service_type = main/tour.
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'bundle_service')->withTimestamps();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /* =======================
     |  Скоупы
     |=======================*/
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }


    /* =======================
     |  Хелперы
     |=======================*/
    public function getPricePrettyAttribute(): string
    {
        if ($this->price === null || !$this->show_price_on_site) {
            return 'По запросу';
        }
        return number_format((int) $this->price, 0, ',', ' ') . ' ₽';
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? '/storage/' . $this->image_path : null;
    }

    public function getSiteImageUrlAttribute(): ?string
    {
        return $this->site_image_path ? '/storage/' . $this->site_image_path : null;
    }

    /**
     * Получить все изображения для галереи
     */
    public function getGalleryImagesAttribute(): array
    {
        if ($this->gallery && is_array($this->gallery)) {
            return $this->gallery;
        }
        
        return [];
    }

    /**
     * Получить первое изображение (титульное)
     */
    public function getFirstImageAttribute(): ?string
    {
        $images = $this->gallery_images;
        return !empty($images) ? $images[0] : null;
    }

    /**
     * Проверить, есть ли связанные заявки или заказы
     */
    public function hasRelatedRecords(): bool
    {
        return $this->applications()->exists() || $this->orders()->exists();
    }

    /**
     * Очистка кеша при изменении бандла
     */
    protected static function boot()
    {
        parent::boot();

        // Принудительно обновляем updated_at при каждом сохранении
        static::saving(function ($bundle) {
            $bundle->updated_at = now();
        });

        static::saved(function ($bundle) {
            Cache::forget('frontend_bundles');
            Cache::forget('frontend_bundles_updated_at');
        });

        static::deleted(function ($bundle) {
            Cache::forget('frontend_bundles');
            Cache::forget('frontend_bundles_updated_at');
        });

        // Очистка кеша при изменении связей с услугами
        static::updated(function ($bundle) {
            if ($bundle->isDirty()) {
                Cache::forget('frontend_bundles');
                Cache::forget('frontend_bundles_updated_at');
            }
        });
    }
}
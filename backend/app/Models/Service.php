<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'site_description',
        'image_path',
        'is_active',
        'site_title',        // заголовок для сайта
        'site_subtitle',     // подзаголовок для сайта
        'site_image_path',   // картинка для сайта
        'price',             // цена услуги
        'service_type',      // тип услуги
        'price_type',        // тип цены
        'max_people',        // максимальное количество людей
        'comment',           // комментарий
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'price'      => 'integer',
        'max_people' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /* =======================
     |  Хелперы
     |=======================*/
    
    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? '/storage/' . $this->image_path : null;
    }

    public function getSiteImageUrlAttribute(): ?string
    {
        return $this->site_image_path ? '/storage/' . $this->site_image_path : null;
    }

    public function getPricePrettyAttribute(): string
    {
        return $this->price ? number_format((int) $this->price, 0, ',', ' ') . ' ₽' : '';
    }

    /* =======================
     |  Хуки для очистки кэша
     |=======================*/
    
    protected static function boot()
    {
        parent::boot();
        
        // Очищаем кэш при создании, обновлении или удалении услуги
        static::saved(function () {
            Cache::forget('frontend_services');
            Cache::forget('frontend_services_updated_at');
        });
        
        static::deleted(function () {
            Cache::forget('frontend_services');
            Cache::forget('frontend_services_updated_at');
        });
    }
}

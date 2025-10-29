<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $table = 'options';

    protected $fillable = [
        'name',
        'description',      // новое поле
        'site_description', // оставляем на всякий случай, если где-то используется
        'image_path',       // путь к изображению
        'price',            // новое поле
        'show_price_on_site', // показывать цену на сайте
        'price_per_person', // множитель за человека
        'price_per_day',    // множитель за сутки
        'is_active',
    ];

    protected $casts = [
        'is_active'         => 'bool',
        'price'             => 'integer',
        'show_price_on_site' => 'bool',
        'price_per_person'  => 'bool',
        'price_per_day'     => 'bool',
    ];

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
}
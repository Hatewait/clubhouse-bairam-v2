<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Option;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    public function services()
    {
        $items = Service::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn ($s) => [
                'id'          => $s->id,
                'name'        => $s->name,
                'title'       => $s->site_title ?? $s->name,
                'description' => $s->site_description ?? $s->description ?? '',
                'image_url'   => $s->site_image_url ?: $s->image_url,
                // Цены скрыты на фронтенде, используются только в админке для расчетов
            ]);

        return response()->json([
            'success' => true,
            'data' => $items,
            'count' => $items->count()
        ]);
    }

    public function options()
    {
        $items = Option::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn ($o) => [
                'id'          => $o->id,
                'name'        => $o->name,
                'description' => $o->description ?? '',
                'image_url'   => $o->image_url,
                // Цены скрыты на фронтенде, используются только в админке для расчетов
                'price_per_person' => (bool) $o->price_per_person,
                'price_per_day'    => (bool) $o->price_per_day,
            ]);

        return response()->json([
            'success' => true,
            'data' => $items,
            'count' => $items->count()
        ]);
    }

    public function bundles()
    {
        $items = Bundle::query()
            ->where('is_active', true)
            ->with('services') // загружаем связанные услуги
            ->orderBy('nights')
            ->get()
            ->map(fn ($b) => [
                'id'          => $b->id,
                'name'        => $b->name,
                'title'       => $b->site_title ?? $b->name,
                'description' => $b->site_description ?? $b->description ?? '',
                'image_url'   => $b->site_image_url ?: $b->image_url,
                'nights'      => (int) $b->nights,
                'price'       => (int) $b->price,
                'price_formatted' => $b->price_pretty,
                'services'    => $b->services->map(fn ($s) => [
                    'id'    => $s->id,
                    'name'  => $s->name,
                    'title' => $s->site_title ?? $s->name,
                ]),
            ]);

        return response()->json([
            'success' => true,
            'data' => $items,
            'count' => $items->count()
        ]);
    }

    /**
     * API для фронтенда с кешированием
     */
    public function frontendBundles()
    {
        return Cache::remember('frontend_bundles', 300, function () { // кеш на 5 минут
            $items = Bundle::query()
                ->where('is_active', true)
                ->with('services')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(fn ($b) => [
                    'id'          => $b->id,
                    'name'        => $b->name,
                    'title'       => $b->name, // Наименование из админки
                    'subtitle'    => $b->site_subtitle ?? '', // Подзаголовок (для сайта)
                    'description' => $b->site_description ?? $b->description ?? '', // Описание (для сайта)
                    'image_url'   => $b->first_image, // Первое изображение из галереи
                    'gallery'     => $b->gallery_images, // Все изображения галереи
                    // Цены скрыты на фронтенде, используются только в админке для расчетов
                    'updated_at'  => $b->updated_at?->toISOString(),
                    'services'    => $b->services->map(fn ($s) => [
                        'id'    => $s->id,
                        'name'  => $s->name,
                        'title' => $s->site_title ?? $s->name,
                    ]),
                ]);

            $cachedAt = now()->toISOString();
            Cache::put('frontend_bundles_updated_at', $cachedAt, 300);
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count(),
                'cached_at' => $cachedAt
            ]);
        });
    }

    public function frontendServices()
    {
        return Cache::remember('frontend_services', 300, function () {
            $items = Service::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(fn ($s) => [
                    'id'          => $s->id,
                    'name'        => $s->name,
                    'title'       => $s->site_title ?? $s->name,
                    'description' => $s->site_description ?? $s->description ?? '',
                    'image_url'   => $s->site_image_url ?: $s->image_url,
                    'price'       => (int) $s->price,
                    'price_formatted' => $s->price_pretty,
                ]);

            $cachedAt = now()->toISOString();
            Cache::put('frontend_services_updated_at', $cachedAt, 300);
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count(),
                'cached_at' => $cachedAt
            ]);
        });
    }

    public function frontendOptions()
    {
        return Cache::remember('frontend_options', 300, function () {
            $items = Option::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(fn ($o) => [
                    'id'          => $o->id,
                    'name'        => $o->name,
                    'description' => $o->description ?? '',
                    'image_url'   => $o->image_url,
                    'price'       => $o->price,
                    'price_formatted' => $o->price_pretty,
                    'price_per_person' => (bool) $o->price_per_person,
                    'price_per_day'    => (bool) $o->price_per_day,
                ]);

            $cachedAt = now()->toISOString();
            Cache::put('frontend_options_updated_at', $cachedAt, 300);
            
            return response()->json([
                'success' => true,
                'data' => $items,
                'count' => $items->count(),
                'cached_at' => $cachedAt
            ]);
        });
    }

    /**
     * Проверка обновлений для фронтенда
     */
    public function checkUpdates()
    {
        $bundlesUpdated = Cache::has('frontend_bundles') ? 
            Cache::get('frontend_bundles_updated_at', now()->toISOString()) : 
            now()->toISOString();
            
        $servicesUpdated = Cache::has('frontend_services') ? 
            Cache::get('frontend_services_updated_at', now()->toISOString()) : 
            now()->toISOString();
            
        $optionsUpdated = Cache::has('frontend_options') ? 
            Cache::get('frontend_options_updated_at', now()->toISOString()) : 
            now()->toISOString();

        return response()->json([
            'success' => true,
            'data' => [
                'bundles_updated_at' => $bundlesUpdated,
                'services_updated_at' => $servicesUpdated,
                'options_updated_at' => $optionsUpdated,
                'server_time' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Очистка кеша форматов отдыха
     */
    public function clearBundlesCache()
    {
        Cache::forget('frontend_bundles');
        Cache::forget('frontend_bundles_updated_at');
        
        return response()->json([
            'success' => true,
                'message' => 'Кеш форматов отдыха очищен'
        ]);
    }
}
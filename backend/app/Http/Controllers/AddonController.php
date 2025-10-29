<?php

namespace App\Http\Controllers;

use App\Models\Addon;

class AddonController extends Controller
{
    /**
     * Возвращает только активные доп.опции, чтобы морда показала чекбоксы.
     * Поля: id, name, price (для информации), is_active (должен быть true).
     */
    public function index()
    {
        $addons = Addon::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'is_active']);

        return response()->json([
            'ok'     => true,
            'items'  => $addons,
            'count'  => $addons->count(),
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Bundle;
use App\Models\Client;
use App\Models\Option;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class IntakeController extends Controller
{
    /**
     * ПИНГ для быстрой проверки доступности API.
     */
    public function ping()
    {
        return response()->json(['pong' => true]);
    }

    /**
     * Активные Форматы отдыха (для формы).
     * Возвращает id, name, nights, price.
     */
    public function bundlesActive()
    {
        $items = Bundle::query()
            ->where('is_active', true)
            ->orderBy('nights')
            ->orderBy('id')
            ->get(['id', 'name', 'nights', 'price']);

        return response()->json($items);
    }

    /**
     * Активные доп. опции для формы.
     */
    public function addonsActive()
    {
        $addons = Option::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'price']);

        $payload = $addons->map(fn ($a) => [
            'id'           => (int) $a->id,
            'name'         => (string) $a->name,
            'price'        => (int) ($a->price ?? 0),
            'price_pretty' => $a->price ? (number_format($a->price, 0, ',', ' ') . ' ₽') : null,
        ]);

        return response()->json($payload);
    }

    /**
     * Приём данных с сайта.
     * Создаёт/находит клиента и создаёт заявку c bundle_id и выбранными addons[].
     * Статус на сайте ВСЕГДА "new".
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:160'],
            'email'         => ['nullable', 'string', 'max:190'],
            'phone'         => ['required', 'string', 'max:64'],
            'booking_date'  => ['required', 'date'],
            'bundle_id'     => ['required', Rule::exists('bundles', 'id')],
            'addons'        => ['nullable', 'array'],
            'addons.*'      => ['integer', Rule::exists('options', 'id')],
            'client_comment'=> ['nullable', 'string', 'max:10000'],
            // На сайте статус игнорируем, но если пришёл — не даём ничего кроме "new"
            'status'        => ['nullable', Rule::in(['new'])],
        ]);

        // Нормализация контактов
        $normEmail = isset($data['email']) ? Client::normalizeEmail($data['email']) : null;
        $normPhone = Client::normalizePhone($data['phone']);
        if (! $normPhone) {
            throw ValidationException::withMessages(['phone' => 'Некорректный номер телефона.']);
        }

        // Проверка формата отдыха и расчёт дат
        $bundle = Bundle::query()->whereKey($data['bundle_id'])->first();
        if (! $bundle) {
            throw ValidationException::withMessages(['bundle_id' => 'Формат отдыха не найден.']);
        }
        $nights = max(2, (int) $bundle->nights); // тех. гарантия: минимум 2 ночи
        $from   = Carbon::parse($data['booking_date'])->startOfDay();
        $to     = (clone $from)->addDays($nights); // выезд на следующий день после последней ночи

        // Оставляем в addons только валидные активные EXTRA-услуги
        $addonIds = collect($data['addons'] ?? [])
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values();

        $validAddonIds = Option::query()
            ->whereIn('id', $addonIds)
            ->where('is_active', true)
            ->pluck('id')
            ->map(fn ($v) => (int) $v);

        [$client, $app] = DB::transaction(function () use ($data, $normEmail, $normPhone, $bundle, $nights, $from, $to, $validAddonIds) {
            // 1) Находим/создаём клиента
            $client = Client::query()
                ->when($normEmail, fn ($q) => $q->orWhere('email', $normEmail))
                ->orWhere('phone', $normPhone)
                ->first();

            if (! $client) {
                $client = Client::create([
                    'name'          => $data['name'],
                    'email'         => $normEmail,
                    'phone'         => $normPhone,
                    'comment'       => null,
                    'client_wishes' => null,
                ]);
            } else {
                $upd = [];
                if (empty($client->name) && !empty($data['name'])) $upd['name']  = $data['name'];
                if ($normEmail && empty($client->email))           $upd['email'] = $normEmail;
                if ($upd) $client->update($upd);
            }

            // 2) Создаём заявку (ВСЕГДА статус new на сайте)
            $app = Application::create([
                'client_id'        => $client->id,
                'service_id'       => null,                // теперь не используем при форматах отдыха
                'bundle_id'        => $bundle->id,
                'booking_date'     => $from->toDateString(),
                'booking_end_date' => $to->toDateString(),
                'people_count'     => 1,
                'status'           => Application::STATUS_NEW,
                'client_wishes'    => $data['client_comment'] ?? null,
                'comment'          => null,
                'nights'           => $nights,
                'total_price'      => 0,                   // расчёт позже правилами/в админке
            ]);

            // 3) Привязка доп. опций (через pivot)
            if ($validAddonIds->isNotEmpty()) {
                // Используем связь Application::addons() для синхронизации
                $app->addons()->sync($validAddonIds->mapWithKeys(fn ($id) => [$id => ['quantity' => 1]]));
            }

            return [$client, $app];
        });

        return response()->json([
            'ok'             => true,
            'client_id'      => $client->id,
            'application_id' => $app->id,
            'bundle_id'      => $app->bundle_id,
            'nights'         => (int) $app->nights,
            'booking_date'   => $app->booking_date?->toDateString(),
            'booking_end'    => $app->booking_end_date?->toDateString(),
        ], 201);
    }
}
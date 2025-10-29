<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IntakeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Api\CalendarController;

Route::post('/intake', [IntakeController::class, 'store']);

// read-only каталоги для фронта
Route::get('/services', [CatalogController::class, 'services']);
Route::get('/options',  [CatalogController::class, 'options']);
Route::get('/bundles',  [CatalogController::class, 'bundles']);

// API для фронтенда с кешированием
Route::get('/frontend/bundles', [CatalogController::class, 'frontendBundles']); // Форматы отдыха
Route::get('/frontend/services', [CatalogController::class, 'frontendServices']);
Route::get('/frontend/options', [CatalogController::class, 'frontendOptions']);

// API для проверки обновлений
Route::get('/frontend/check-updates', [CatalogController::class, 'checkUpdates']);

// API для очистки кеша
Route::post('/frontend/clear-cache', [CatalogController::class, 'clearBundlesCache']);

// API для формы приема заявок
Route::get('/bundles-active', [IntakeController::class, 'bundlesActive']);
Route::get('/addons-active', [IntakeController::class, 'addonsActive']);

// API для формы обратной связи
Route::post('/feedback', [FeedbackController::class, 'store']);

// API для формы бронирования путешествия
Route::post('/booking', [BookingController::class, 'store']);

// API календаря
Route::prefix('calendar')->group(function () {
    Route::get('/dates', [CalendarController::class, 'getDates']);
    Route::get('/year', [CalendarController::class, 'getYear']);
    Route::get('/month', [CalendarController::class, 'getMonth']);
    Route::get('/check', [CalendarController::class, 'checkDate']);
    Route::get('/stats', [CalendarController::class, 'getStats']);
    Route::get('/next-available', [CalendarController::class, 'getNextAvailable']);
    Route::get('/events', [CalendarController::class, 'getEvents']);
    Route::post('/block', [CalendarController::class, 'blockDate']);
    Route::post('/unblock', [CalendarController::class, 'unblockDate']);
});

// простая проверка жизни API
Route::get('/ping', fn () => response()->json(['pong' => true]));
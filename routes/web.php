<?php

use App\Http\Controllers\BusScheduleController;

use Inertia\Inertia;
use App\Http\Controllers\Api\BusController\{
    DestroyController,
    IndexController,
    ShowController,
    StoreController,
    UpdateController,
};

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BusController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

// Группа маршрутов API для работы с автобусами
Route::prefix('api')->group(function () {
    // Поиск автобусов с указанием остановок
    Route::get('/find-bus', [IndexController::class, '__invoke'])->name('api.find-bus');

    // Создание автобуса
    Route::post('/buses', StoreController::class)->name('api.store');

    // Показ информации об одном автобусе по ID
    Route::get('/buses/{id}', ShowController::class)->name('api.show');

    // Показ всех автобусов
    Route::get('/buses', [ShowController::class, '__invoke'])->name('api.index');

    // Для отображения формы редактирования маршрута
    Route::get('/buses/{id}/edit', [UpdateController::class, 'edit'])->name('api.edit');

    // Обновление данных автобуса
    Route::patch('/buses/{id}', UpdateController::class)->name('api.update');

    // Удаление автобуса
    Route::delete('/buses/{routeId}', DestroyController::class)->name('api.destroy');

    // Получение расписания автобусов
    Route::get('/bus-schedules', [BusScheduleController::class, 'index'])->name('api.bus-schedules');
});

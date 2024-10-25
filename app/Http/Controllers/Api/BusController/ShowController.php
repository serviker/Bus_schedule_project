<?php

namespace App\Http\Controllers\Api\BusController;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShowController extends Controller
{
    public function __invoke(Request $request)
    {
        // Получаем параметры фильтрации из запроса
        $busName = $request->input('bus_name'); // Фильтр по имени автобуса
        $routeId = $request->input('route_id'); // Фильтр по маршруту

        // Получаем маршруты с остановками
        $routesQuery = Route::with('stops');

        // Применяем фильтр по маршруту, если задан
        if ($routeId) {
            $routesQuery->where('id', $routeId);
        }

        // Получаем маршруты
        $routes = $routesQuery->get();

        // Если указан фильтр по имени автобуса, получаем автобусы
        $buses = Bus::when($busName, function ($query) use ($busName) {
            return $query->where('name', 'like', '%' . $busName . '%');
        })->get();

        // Для хранения уникальных остановок
        $uniqueStops = collect();

        // Перебираем маршруты и их остановки, добавляем уникальные остановки
        foreach ($routes as $route) {
            foreach ($route->stops as $stop) {
                // Добавляем остановку только если ее еще нет в коллекции
                if (!$uniqueStops->contains('id', $stop->id)) {
                    $uniqueStops->push($stop);
                }
            }
        }

        // Возвращаем данные в представление
        return view('api.index', compact('buses', 'routes', 'uniqueStops'));

    }
}

<?php

namespace App\Http\Controllers\Api\BusController;

use App\Http\Controllers\Controller;
use App\Models\BusStop;
use App\Models\Route;
use App\Models\Stop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Bus;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        // Валидация входных данных для маршрута, начальной остановки и интервалов
        $request->validate([
            'route_number' => 'required|integer|min:1',
            'initial_stop_name' => 'required|string|max:255',
            'departure_time' => 'required|date_format:H:i',
            'bus_count' => 'required|integer|min:1',
            'bus_departure_interval' => 'required|integer|min:1',
            'stop_interval' => 'required|integer|min:1',
        ]);

        // Создание нового маршрута с номером маршрута
        $route = Route::create([
            'name' => 'Маршрут №' . $request->input('route_number'), // Временно без конечной остановки
            'route_number' => $request->input('route_number'),
        ]);

        // Получаем или создаем начальную остановку
        $initialStop = Stop::firstOrCreate([
            'name' => $request->input('initial_stop_name'),
        ]);

        // Получаем значения из запроса
        $busCount = $request->input('bus_count');
        $busDepartureInterval = $request->input('bus_departure_interval');
        $stopInterval = $request->input('stop_interval');

        // Начальное время отправления
        $departureTime = Carbon::createFromTimeString($request->input('departure_time'));

        // Создаем автобусы и привязываем их к маршруту и остановкам с интервалами
        for ($busIndex = 0; $busIndex < $busCount; $busIndex++) {
            // Создаем автобус
            $bus = Bus::create([
                'name' => "Автобус №" . ($busIndex + 1),
                'route_id' => $route->id,
            ]);

            // Рассчитываем время отправления для текущего автобуса
            $currentDepartureTime = $departureTime->copy()->addMinutes($busDepartureInterval * $busIndex);

            // Привязка начальной остановки к автобусу
            BusStop::create([
                'bus_id' => $bus->id,
                'stop_id' => $initialStop->id,
                'route_id' => $route->id,
                'arrival_time' => $currentDepartureTime->toTimeString(),
                'stop_order' => 1,
                'interval' => $stopInterval,
            ]);
        }

        return response()->json([
            'message' => 'Маршрут, автобусы и остановки успешно добавлены',
            'route' => $route,
            'initial_stop' => $initialStop,
        ], 201);
    }
}


<?php

namespace App\Http\Controllers\Api\BusController;

use App\Http\Controllers\Controller;
use App\Models\Stop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Route;


use App\Models\BusStop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateController extends Controller
{
    // Метод для отображения формы редактирования маршрута
    public function edit($id)
    {
        try {
            // Находим маршрут по ID
            $route = Route::findOrFail($id);

            // Получаем bus_id через связанные остановки
            $busStop = $route->stops()->select('bus_id')->first();
            $busId = $busStop ? $busStop->bus_id : null;

            $busStop = BusStop::find($id);
            $interval = $busStop->interval;
           // Log::info('Received busId:', ['interval' => $interval]);
            // Получаем все уникальные остановки
            $uniqueStops = Stop::all();
            //dd($uniqueStops->first());

            // Возвращаем представление с данными маршрута и остановками
            return view('api.update', [
                'route' => $route,
                'uniqueStops' => $uniqueStops,
                'busId' => $busId,
                'interval' => $interval,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }

    // Метод для обновления маршрута
    public function __invoke(Request $request, $id)
    {
        // Валидация входящих данных
        $request->validate([
            'stops' => 'nullable|array',
            'stops.*' => 'exists:bus_stops,id',
            'stop_names' => 'required|array',
            'new_stop_name' => 'nullable|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'interval' => 'required|integer',
        ]);

       // Log::info('Request data before validation:', $request->all());

        try {
            $route = Route::findOrFail($id);
            $buses = $route->buses;
            // Получаем bus_id из запроса
            $busId = $request->input('bus_id');

            // Обработка остановок
            if ($request->stops) {
                $existingStops = $route->stops->pluck('id')->toArray();
                $removedStops = array_diff($existingStops, $request->stops);

                // Удаление остановок, которые больше не включены
                foreach ($removedStops as $stopId) {
                    $route->stops()->detach($stopId);
                }

                foreach ($request->stops as $stopId) {
                    if (!$route->stops()->where('stop_id', $stopId)->exists()) {
                        // Вычисляем stop_order
                        $stopOrder = $route->stops()->max('stop_order') + 1; // Получаем максимальное значение stop_order и добавляем 1

                        foreach ($buses as $bus) {
                            // Получаем максимальное значение stop_order для данного автобуса
                            $maxStopOrder = $route->stops()
                                ->where('bus_id', $bus->id)
                                ->max('stop_order');

                            // Теперь находим последнюю остановку с этим stop_order
                            $lastStop = $route->stops()
                                ->where('bus_id', $bus->id)
                                ->where('stop_order', $maxStopOrder)
                                ->first();

                            Log::info('Полученный maxStopOrder:', ['stop_order' => $maxStopOrder]);

                            if ($lastStop) {
                                // Извлекаем arrival_time из pivot для последней остановки
                                $arrivalTime = $lastStop->pivot->arrival_time;

                                $interval = (int) $request->input('interval'); // Интервал из запроса
                                $newArrivalTime = Carbon::parse($arrivalTime)->addMinutes($interval);

                                // Добавление новой остановки с рассчитанным временем прибытия
                                $route->stops()->attach($stopId, [
                                    'bus_id' => $bus->id,
                                    'arrival_time' => $newArrivalTime->format('H:i:s'),
                                    'stop_order' => $maxStopOrder + 1,
                                ]);

                                Log::info("Расчетное время прибытия для новой остановки: " . $newArrivalTime);
                            } else {
                                Log::error(" Остановка с ID{$stopId} не найдена на маршруте.");
                                // Пропустите итерацию, если остановка не найдена
                                continue;
                            }
                        }
                    }
                }

                // Обновление названий остановок
                foreach ($request->stop_names as $stopId => $newName) {
                    $stop = Stop::find($stopId);
                    if ($stop && $newName !== $stop->name) {
                        $stop->update(['name' => $newName]);
                    }
                }

                // Добавление новой остановки, если указано
                if ($request->new_stop_name) {
                    $newStop = Stop::create(['name' => $request->new_stop_name]);
                    $stopOrder = $route->stops()->max('stop_order') + 1;

                    foreach ($buses as $bus) {
                        $maxStopOrder = $route->stops()
                            ->where('bus_id', $bus->id)
                            ->max('stop_order');

                        // Находим последнюю остановку с этим stop_order
                        $lastStop = $route->stops()
                            ->where('bus_id', $bus->id)
                            ->where('stop_order', $maxStopOrder)
                            ->first();

                        if ($lastStop) {
                            // Извлекаем arrival_time из pivot для последней остановки
                            $arrivalTime = $lastStop->pivot->arrival_time;
                            $interval = (int) $request->input('interval');
                            $newArrivalTime = Carbon::parse($arrivalTime)->addMinutes($interval);

                            // Добавление новой остановки с рассчитанным временем прибытия
                            $route->stops()->attach($newStop->id, [
                                'bus_id' => $bus->id,
                                'arrival_time' => $newArrivalTime->format('H:i:s'),
                                'stop_order' => $stopOrder,
                            ]);
                        }
                    }
                }
                foreach ($buses as $bus) {
                    // Находим последнюю остановку для любого автобуса
                    $maxStopOrder = $route->stops()
                        ->where('bus_id', $bus->id)
                        ->max('stop_order');

                    if ($maxStopOrder) {
                        $lastStop = $route->stops()
                            ->where('bus_id', $bus->id)
                            ->where('stop_order', $maxStopOrder)
                            ->first();

                        if ($lastStop) {
                            // Базовое название маршрута без конечной остановки
                            $baseName = 'Маршрут №' . $route->route_number;

                            // Если текущее имя маршрута уже содержит конечную остановку, убираем её
                            $currentName = $route->name;
                            if (str_contains($currentName, 'в сторону ост.')) {
                                $currentName = substr($currentName, 0, strpos($currentName, 'в сторону ост.'));
                            }

                            // Обновляем имя маршрута, добавляя актуальную конечную остановку
                            $route->update([
                                'name' => trim($currentName) . ' в сторону ост. ' . $lastStop->name,
                            ]);
                            break; // Достаточно обновить один раз
                        }
                    }
                }



                return response()->json(['message' => 'Маршрут успешно обновлен!']);
            }

            return response()->json(['message' => 'Остановки не переданы. Никаких изменений не внесено.'], 400);

        } catch (\Exception $e) {
            Log::error('Error updating route: ' . $e->getMessage());
            return response()->json(['message' => 'Ошибка: ' . $e->getMessage()], 500);
        }
    }
}



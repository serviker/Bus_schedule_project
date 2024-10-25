<?php

namespace App\Http\Controllers\Api\BusController;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Stop;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::info('Запрос на поиск автобусов', [
            'from' => $request->input('from'),
            'to' => $request->input('to'),
        ]);

        $from = $request->input('from');
        $to = $request->input('to');

        // Получаем названия остановок
        $fromStop = Stop::find($from);
        $toStop = Stop::find($to);

        if ($fromStop && $toStop) {
            // Получаем текущее московское время
            $currentTime = Carbon::now('Europe/Moscow');

            // Получаем автобусы с остановками
            $buses = Bus::whereHas('stops', function ($query) use ($from) {
                $query->where('stop_id', $from);
            })->whereHas('stops', function ($query) use ($to) {
                $query->where('stop_id', $to);
            })->get();

            Log::info('Найденные автобусы', [
                'buses_count' => $buses->count(),
                'buses' => $buses->toArray(),
            ]);

            $result = [];
            foreach ($buses as $bus) {
                // Проверяем, идет ли автобус от 'from' к 'to'
                $fromOrder = $bus->stops()->where('stop_id', $from)->first()->pivot->stop_order;
                $toOrder = $bus->stops()->where('stop_id', $to)->first()->pivot->stop_order;

                // Если порядок остановок неверный, пропускаем автобус
                if ($fromOrder >= $toOrder) {
                    continue; // Пропускаем, если автобус не идет от 'from' к 'to'
                }

                // Получаем все запланированные времена прибытия на остановку 'from'
                $arrivals = $bus->stops()
                    ->where('stop_id', $from)
                    ->withPivot('arrival_time')
                    ->orderBy('bus_stops.stop_order')
                    ->get();

                // Фильтруем времена прибытия, оставляя только те, что идут после текущего времени
                $nextArrivals = $arrivals->filter(function ($stop) use ($currentTime) {
                    // Предполагаем, что время в базе уже хранится в московском часовом поясе
                    $arrivalTime = Carbon::parse($stop->pivot->arrival_time, 'Europe/Moscow');
                    return $arrivalTime->greaterThanOrEqualTo($currentTime); // Сравниваем с текущим временем
                });

                // Ограничиваем выборку до трех ближайших времен
                if ($nextArrivals->count() > 0) {
                    $nextArrivals = $nextArrivals->pluck('pivot.arrival_time')->take(3);
                } else {
                    continue; // Если нет доступных времен, пропускаем автобус
                }

                // Формируем ключ маршрута с указанием номера автобуса
                $key =  $bus->route->name;

                if (!isset($result[$key])) {
                    $result[$key] = [];
                }
                // Добавляем времена в массив маршрута
                $result[$key] = array_merge($result[$key], $nextArrivals->toArray());

                Log::info('Автобус найден', ['bus_id' => $bus->id, 'stop_id' => $from, 'next_arrivals' => $nextArrivals->toArray()]);
            }

            // Преобразуем результат в нужный формат
            $finalResult = [];
            foreach ($result as $route => $times) {
                // Убираем дублирование времени прибытия и сортируем
                $uniqueTimes = array_unique($times);
                sort($uniqueTimes);

                // Ограничиваем количество времён до 3
                $limitedTimes = array_slice($uniqueTimes, 0, 3);

                // Форматируем строку с временем
                $formattedTimes = array_map(function ($time) {
                    return Carbon::parse($time, 'Europe/Moscow')->format('H:i'); // Форматируем время как H:i
                }, $limitedTimes);

                $finalResult[] = [
                    'route' => $route,
                    'next_arrivals' => implode(', ', $formattedTimes), // Объединяем времена в строку
                ];
            }

            Log::info('Результаты поиска', ['result' => $finalResult]);

            return view('api.find-bus', [
                'from' => $fromStop->name,
                'to' => $toStop->name,
                'buses' => $finalResult,
            ]);
        }

        Log::warning('Критерии поиска не указаны', [
            'from' => $from,
            'to' => $to,
        ]);

        return view('api.find-bus', ['buses' => []]); // Возвращаем пустой массив, если автобусы не найдены
    }

}


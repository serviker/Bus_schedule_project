<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Route;
use Illuminate\Database\Seeder;
use App\Models\BusStop;
use App\Models\Stop;
use Carbon\Carbon;

//   php artisan migrate:fresh --seed

class BusStopSeeder extends Seeder
{
    public function run()
    {
        // Создаем маршруты для автобуса 11 (вперед и назад)
        $route11_forward = Route::firstOrCreate(['name' => 'Маршрут №11 в сторону ост. Попова']);
        $route11_backward = Route::firstOrCreate(['name' => 'Маршрут №11 в сторону ост. Цирка']);

        // Создаем маршруты для автобуса 21 (вперед и назад)
        $route21_forward = Route::firstOrCreate(['name' => 'Маршрут №21 в сторону ост. Ленина']);
        $route21_backward = Route::firstOrCreate(['name' => 'Маршрут №21 в сторону ост. Пушкина']);

        // Добавляем автобусы и остановки для маршрута 11 (вперед и назад)
        $this->createBusesWithStops($route11_forward, ['Цирк', 'ул. Некрасова', 'Театр драмы', 'Сквер Лермонтова', 'Университет', 'ул. Ленина', 'ул. Маяковского', 'ул. Пушкина', 'Театр оперы', 'ул. Попова'], '07:45', 40);
        $this->createBusesWithStops($route11_backward, ['ул. Попова', 'Театр оперы', 'ул. Мельника', 'Завод ТочМаш', 'ул. Бунина', 'ТРЦ Волна', 'ул. Университетская', 'ул. Некрасова', 'Цирк'], '07:30', 40);

        // Добавляем автобусы и остановки для маршрута 21 (вперед и назад)
        $this->createBusesWithStops($route21_forward, ['ул. Пушкина', 'ул. Лыскова', 'Университет', 'Сквер Лермонтова', 'Театр драмы', 'ул. Некрасова', 'Цирк', 'Театр Миниатюры', 'ул. Чехова', 'ул. Ленина'], '07:30', 40);
        $this->createBusesWithStops($route21_backward, ['ул. Ленина', 'ул. Чехова', 'Театр Миниатюры', 'Цирк', 'ул. Некрасова', 'Театр драмы', 'Сквер Лермонтова', 'Университет', 'ул. Лыскова', 'ул. Пушкина'], '07:45', 40);
    }

    private function createBusesWithStops($route, $stops, $startTime, $interval)
    {
        $start = Carbon::createFromTimeString($startTime);

        for ($busIndex = 0; $busIndex < 15; $busIndex++) {
            // Создаем автобус с уникальным именем
            $bus = Bus::firstOrCreate(['name' => ($busIndex + 1) . $route->name, 'route_id' => $route->id]);

            // Увеличиваем время отправления на 30 минут для каждого автобуса
            $departureTime = $start->copy()->addMinutes(30 * $busIndex);
            $this->createBusStops($bus, $route, $stops, $departureTime->toTimeString(), $interval);
        }
    }

    private function createBusStops($bus, $route, $stops, $startTime, $interval)
    {
        $start = Carbon::createFromTimeString($startTime);

        foreach ($stops as $index => $stopName) {
            $stop = Stop::firstOrCreate(['name' => $stopName]);
            BusStop::create([
                'bus_id' => $bus->id,
                'stop_id' => $stop->id,
                'route_id' => $route->id,
                'arrival_time' => $start->copy()->addMinutes($interval * $index)->toTimeString(),
                'stop_order' => $index + 1,
                'interval' => $interval,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\BusStop;
use Illuminate\Http\Request;

class BusScheduleController extends Controller
{
    public function index()
    {
        $busSchedules = BusStop::all(); // Получаем все записи из таблицы bus_stops
        return response()->json($busSchedules); // Возвращаем данные в формате JSON
    }
}

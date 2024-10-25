<?php

namespace App\Http\Controllers\Api\BusController;

use App\Http\Controllers\Controller;
use App\Models\Bus;

class EditController extends Controller
{
    public function __invoke($id)
    {
        $bus = Bus::findOrFail($id);
        // Логика для редактирования автобуса
        return response()->json($bus);
    }
}

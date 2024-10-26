<?php

namespace App\Http\Controllers\Api\BusController;

use App\Models\Bus;
use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Support\Facades\Log;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $route = Route::findOrFail($id);
        Log::info('Received busId:', ['bus' => $route]);
        $route->delete();

        return response()->json(['message' => 'Bus deleted successfully']);
    }
}

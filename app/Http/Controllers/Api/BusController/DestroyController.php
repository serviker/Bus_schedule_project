<?php

namespace App\Http\Controllers\Api\BusController;

use App\Models\Bus;
use App\Http\Controllers\Controller;

class DestroyController extends Controller
{
    public function __invoke($id)
    {
        $bus = Bus::findOrFail($id);
        $bus->delete();

        return response()->json(['message' => 'Bus deleted successfully']);
    }
}

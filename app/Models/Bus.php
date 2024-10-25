<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{

    use HasFactory,SoftDeletes;
    protected $fillable = ['name', 'route_id'];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function stops()
    {
        return $this->belongsToMany(Stop::class, 'bus_stops')
            ->withPivot('arrival_time', 'stop_order')
            ->orderBy('stop_order');
    }
}

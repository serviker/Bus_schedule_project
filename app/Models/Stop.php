<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stop extends Model
{
    use SoftDeletes, HasFactory;
    protected $fillable = ['name', 'route_id'];

    public function buses()
    {
        return $this->belongsToMany(Bus::class, 'bus_stops')
            ->withPivot('arrival_time', 'stop_order')
            ->orderBy('stop_order');
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function busStop()
    {
        return $this->hasMany(BusStop::class);
    }
}

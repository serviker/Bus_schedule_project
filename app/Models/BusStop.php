<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusStop extends Model
{

    use HasFactory, SoftDeletes;
    protected $table = 'bus_stops';

    protected $fillable = ['route_id', 'stop_id', 'bus_id', 'arrival_time', 'stop_order', 'interval', 'other_attributes...'];

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}

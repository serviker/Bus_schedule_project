<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'other_attributes...'];

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function stops()
    {
        return $this->belongsToMany(Stop::class, 'bus_stops')
            ->withPivot('arrival_time', 'stop_order')
            ->orderBy('stop_order');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Odp extends Model
{
    protected $fillable = [
        'name',
        'odc_id',
        'latitude',
        'longitude',
        'description',
        'capacity',
        'filled',
    ];

    public function odc()
    {
        return $this->belongsTo(Odc::class);
    }

    public function onus()
    {
        return $this->hasMany(Onu::class);
    }
}

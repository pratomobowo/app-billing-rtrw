<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Odc extends Model
{
    use HasFactory;

    protected $fillable = [
        'olt_id',
        'name',
        'latitude',
        'longitude',
        'description',
    ];

    public function olt()
    {
        return $this->belongsTo(Olt::class);
    }

    public function odps()
    {
        return $this->hasMany(Odp::class);
    }
}

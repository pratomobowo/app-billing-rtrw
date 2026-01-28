<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Onu extends Model
{
    protected $fillable = [
        'olt_id',
        'name',
        'serial_number',
        'interface',
        'signal',
        'last_check',
    ];

    public function olt()
    {
        return $this->belongsTo(Olt::class);
    }
}

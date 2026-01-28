<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Olt extends Model
{
    protected $fillable = [
        'name',
        'ip_address',
        'type',
        'username',
        'password',
        'port',
    ];

    public function onus()
    {
        return $this->hasMany(Onu::class);
    }
}

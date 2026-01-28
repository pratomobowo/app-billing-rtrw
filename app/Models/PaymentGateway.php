<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $guarded = [];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

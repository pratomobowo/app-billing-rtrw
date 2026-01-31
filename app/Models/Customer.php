<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}

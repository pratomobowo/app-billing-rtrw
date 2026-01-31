<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}

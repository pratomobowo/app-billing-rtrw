<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GowaDevice extends Model
{
    protected $guarded = [];

    // 'status' can be: disconnected, connected, scanning
}

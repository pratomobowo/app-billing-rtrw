<?php

namespace App\Models\Radius;

use Illuminate\Database\Eloquent\Model;

class RadAcct extends Model
{
    protected $table = 'radacct';
    protected $primaryKey = 'radacctid';
    public $timestamps = false;
    protected $guarded = [];
}

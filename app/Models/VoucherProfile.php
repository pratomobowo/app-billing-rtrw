<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherProfile extends Model
{
    protected $guarded = [];

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }
}

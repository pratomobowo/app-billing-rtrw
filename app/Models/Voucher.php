<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(VoucherProfile::class, 'voucher_profile_id');
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }
}

<?php

namespace onlineshopping\userdiscounts\src\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDiscount extends Pivot
{
    protected $table = 'user_discounts';

    protected $fillable = ['user_id', 'discount_id', 'used_at'];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('user_discounts.user_model'));
    }
}


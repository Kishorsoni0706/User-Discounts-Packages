<?php


namespace onlineshopping\userdiscounts\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountAudit extends Model
{
    protected $fillable = ['discount_id', 'action', 'performed_by', 'performed_at', 'details'];

    protected $casts = [
        'performed_at' => 'datetime',
        'details' => 'array',
    ];

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('user_discounts.user_model'), 'performed_by');
    }
}

<?php


namespace UserDiscounts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    protected $fillable = ['name', 'percentage', 'active', 'expires_at', 'usage_limit_per_user'];

    protected $casts = [
        'active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        return $this->active && (is_null($this->expires_at) || $this->expires_at->isFuture());
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('user_discounts.user_model'), 'user_discounts')
                    ->withPivot('used_at')
                    ->withTimestamps();
    }

    public function audits(): HasMany
    {
        return $this->hasMany(DiscountAudit::class);
    }
}

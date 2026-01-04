<?php


class DiscountService
{
    public function assign(User $user, Discount $discount)
    {
        if (!$discount->isActive()) return;

        UserDiscount::firstOrCreate([
            'user_id' => $user->id,
            'discount_id' => $discount->id,
        ]);

        event(new DiscountAssigned($user, $discount));

        $this->audit($user, $discount, 'assigned');
    }

    public function revoke(User $user, Discount $discount)
    {
        UserDiscount::where('user_id', $user->id)
            ->where('discount_id', $discount->id)
            ->delete();

        event(new DiscountRevoked($user, $discount));

        $this->audit($user, $discount, 'revoked');
    }

    public function eligibleFor(User $user): Collection
    {
        return UserDiscount::with('discount')
            ->where('user_id', $user->id)
            ->get()
            ->filter(fn($ud) => $ud->discount->isActive())
            ->filter(fn($ud) => is_null($ud->discount->usage_limit_per_user) || $ud->times_used < $ud->discount->usage_limit_per_user)
            ->map(fn($ud) => $ud->discount);
    }

    public function apply(User $user, float $amount): float
    {
        return DB::transaction(function () use ($user, $amount) {
            $discounts = $this->eligibleFor($user);

            if (config('user_discounts.stacking_order') === 'highest_first') {
                $discounts = $discounts->sortByDesc('percentage');
            }

            $totalDiscount = 0;
            $maxCap = config('user_discounts.max_percentage_cap');

            foreach ($discounts as $discount) {
                if ($totalDiscount >= $maxCap) break;

                $applyAmount = min($discount->percentage, $maxCap - $totalDiscount);
                $totalDiscount += $applyAmount;

                // Increment usage safely
                UserDiscount::where('user_id', $user->id)
                    ->where('discount_id', $discount->id)
                    ->lockForUpdate()
                    ->increment('times_used');

                $this->audit($user, $discount, 'applied');
                event(new DiscountApplied($user, $discount));
            }

            $discountValue = ($totalDiscount / 100) * $amount;
            $round = config('user_discounts.rounding');

            $discountedAmount = match ($round) {
                'ceil' => ceil($amount - $discountValue),
                'floor' => floor($amount - $discountValue),
                default => round($amount - $discountValue),
            };

            return max(0, $discountedAmount);
        });
    }

    private function audit(User $user, Discount $discount, string $action)
    {
        DiscountAudit::create([
            'user_id' => $user->id,
            'discount_id' => $discount->id,
            'action' => $action,
        ]);
    }
}

<?php


test('assign → eligible → apply works', function () {
    $user = User::factory()->create();
    $discount = Discount::factory()->create(['percentage' => 10]);

    (new DiscountService())->assign($user, $discount);

    $eligible = (new DiscountService())->eligibleFor($user);
    expect($eligible)->toHaveCount(1);

    $finalAmount = (new DiscountService())->apply($user, 100);
    expect($finalAmount)->toBe(90.0);
});


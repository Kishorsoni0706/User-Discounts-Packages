<?php

class UserDiscount extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \onlineshopping\userdiscounts\src\Services\DiscountService::class;
    }
}

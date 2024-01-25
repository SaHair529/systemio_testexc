<?php

namespace App\Service;

class PriceCalculatorService
{
    private const COUPONS = [
        'F9'  => 9,  # Fixed
        'P20' => 20  # Percentage
    ];

    private const TAX_NUMBERS = [
        'DE123456789' => 19,      # Germany
        'IT12345678901' => 22,    # Italy,
        'GR123456789' => 24,      # Greece
        'FRYY123456789' => 20     # France
    ];

    public static function calculatePriceByTaxNumber(int $price, string $taxNumber): int
    {
        if (!isset(self::TAX_NUMBERS[$taxNumber]))
            return -1;

        return $price + ($price * (self::TAX_NUMBERS[$taxNumber] / 100));
    }

    public static function calculatePriceByCoupon(int $price, string $coupon): int
    {
        if (!isset(self::COUPONS[$coupon]))
            return $price;

        if ($coupon[0] === 'F')
            return $price - self::COUPONS[$coupon];

        return $price - ($price * (self::COUPONS[$coupon] / 100));
    }
}
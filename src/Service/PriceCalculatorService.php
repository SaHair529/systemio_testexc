<?php

namespace App\Service;

class PriceCalculatorService
{
    private const PRODUCT_PRICES = [ # Не совсем понятно как по-другому реализовать без CRUD, поэтому просто закинул в массив
        '1' => 100, # iphone
        '2' => 20,  # Наушники
        '3' => 10   # Чехол
    ];

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

    public static function calculatePrice(array $requestData): int
    {
        $resultPrice = self::calculatePriceByTaxNumber(self::PRODUCT_PRICES[$requestData['product']], $requestData['taxNumber']);
        if ($resultPrice === -1)
            return $resultPrice;

        if (isset($requestData['couponCode']))
            $resultPrice = self::calculatePriceByCoupon($resultPrice, $requestData['couponCode']);

        return $resultPrice;
    }

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
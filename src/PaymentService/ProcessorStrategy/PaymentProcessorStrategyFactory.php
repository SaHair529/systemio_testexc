<?php

namespace App\PaymentService\ProcessorStrategy;

class PaymentProcessorStrategyFactory
{
    public static function createStrategy(string $type)
    {
        switch ($type) {
            case 'paypal':
                return new PaypalPaymentProcessorStrategy();
            case 'stripe':
                return new StripePaymentProcessorStrategy();
        }
    }
}
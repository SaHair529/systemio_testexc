<?php

namespace App\PaymentService\ProcessorStrategy;

use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorStrategy implements PaymentProcessorStrategyInterface
{

    public function process(float $price): bool
    {
        $stripePaymentProcessor = new StripePaymentProcessor();

        return $stripePaymentProcessor->processPayment($price);
    }
}
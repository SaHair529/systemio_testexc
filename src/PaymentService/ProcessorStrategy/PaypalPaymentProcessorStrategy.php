<?php

namespace App\PaymentService\ProcessorStrategy;

use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentProcessorStrategy implements PaymentProcessorStrategyInterface
{
    public function process(float $price): bool
    {
        $paypalPaymentProcessor = new PaypalPaymentProcessor();

        try {
            $paypalPaymentProcessor->pay((int)$price);
        }
        catch (Exception $e) {
            return false;
        }

        return true;
    }
}
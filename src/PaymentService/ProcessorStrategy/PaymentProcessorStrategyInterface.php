<?php

namespace App\PaymentService\ProcessorStrategy;

interface PaymentProcessorStrategyInterface
{
    public function process(float $price): bool;
}
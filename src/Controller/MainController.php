<?php

namespace App\Controller;

use App\PaymentService\ProcessorStrategy\PaymentProcessorStrategyFactory;
use App\Service\PriceCalculatorService;
use App\Service\PurchaseService;
use App\Service\RequestsValidator;
use App\Service\ResponseCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/calculate-price', name: 'calculate_price', methods: 'POST')]
    public function calculatePrice(Request $request, RequestsValidator $requestsValidator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true) ?? [];
        $validationViolations = $requestsValidator->validateCalculatePrice($requestData);

        if (!empty($validationViolations))
            return ResponseCreator::invalidRequest($validationViolations);

        $resultPrice = PriceCalculatorService::calculatePrice($requestData);
        if ($resultPrice === -1)
            return ResponseCreator::wrongTaxNumber();

        return ResponseCreator::calculateResponse($resultPrice);
    }

    #[Route('/purchase', name: 'purchase', methods: 'POST')]
    public function purchase(Request $request, RequestsValidator $requestsValidator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true) ?? [];
        $validationViolations = $requestsValidator->validatePurchase($requestData);

        if (!empty($validationViolations))
            return ResponseCreator::invalidRequest($validationViolations);

        $resultPrice = PriceCalculatorService::calculatePrice($requestData);
        if ($resultPrice === -1)
            return ResponseCreator::wrongTaxNumber();

        $paymentStrategy = PaymentProcessorStrategyFactory::createStrategy($requestData['paymentProcessor']);
        $paymentResult = $paymentStrategy->process((float) $resultPrice);

        return ResponseCreator::paymentResultDependentResponse($paymentResult);
    }
}

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
    private array $productPrices = [ # Не совсем понятно как по-другому реализовать без CRUD, поэтому просто закинул в массив
        '1' => 100, # iphone
        '2' => 20,  # Наушники
        '3' => 10   # Чехол
    ];

    #[Route('/calculate-price', name: 'calculate_price', methods: 'POST')]
    public function calculatePrice(Request $request, RequestsValidator $requestsValidator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true) ?? [];
        $validationViolations = $requestsValidator->validateCalculatePrice($requestData);

        if (!empty($validationViolations))
            return ResponseCreator::invalidRequest($validationViolations);

        $resultPrice = PriceCalculatorService::calculatePriceByTaxNumber($this->productPrices[$requestData['product']], $requestData['taxNumber']);
        if ($resultPrice === -1)
            return ResponseCreator::wrongTaxNumber();

        if (isset($requestData['couponCode']))
            $resultPrice = PriceCalculatorService::calculatePriceByCoupon($resultPrice, $requestData['couponCode']);

        return ResponseCreator::calculateResponse($resultPrice);
    }

    #[Route('/purchase', name: 'purchase', methods: 'POST')]
    public function purchase(Request $request, RequestsValidator $requestsValidator): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true) ?? [];
        $validationViolations = $requestsValidator->validatePurchase($requestData);

        if (!empty($validationViolations))
            return ResponseCreator::invalidRequest($validationViolations);

        # TODO Добавить расчет стоимость с учетом налогов и купонов

        $paymentStrategy = PaymentProcessorStrategyFactory::createStrategy($requestData['paymentProcessor']);
        $paymentResult = $paymentStrategy->process((float) $this->productPrices[$requestData['product']]);

        return ResponseCreator::paymentResultDependentResponse($paymentResult);
    }
}

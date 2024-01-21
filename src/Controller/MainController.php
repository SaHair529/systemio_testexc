<?php

namespace App\Controller;

use App\Service\PriceCalculatorService;
use App\Service\PurchaseService;
use App\Service\RequestsValidator;
use App\Service\ResponseCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Collection;

class MainController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/calculate-price', name: 'calculate_price', methods: 'POST')]
    public function calculatePrice(Request $request, PriceCalculatorService $calculatorService, RequestsValidator $requestsValidator)
    {
        $requestData = json_decode($request->getContent(), true);
        $validationViolations = $requestsValidator->validateCalculatePrice($requestData);

        if (!empty($validationViolations))
            return ResponseCreator::calculatePrice_invalidRequest($validationViolations);
    }

    #[Route('/purchase', name: 'purchase', methods: 'POST')]
    public function purchase(Request $request, PurchaseService $purchaseService)
    {
        $constraints = new Collection([
            'product' => $this->productConstraints,
            'taxNumber' => $this->taxNumberConstraints,
            'couponCode' => $this->couponCodeConstraints,
            'paymentProcessor' => $this->paymentProcessorConstraints
        ]);
    }
}

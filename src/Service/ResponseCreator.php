<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseCreator
{
    public static function invalidRequest(array $validationViolations): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Invalid request data',
            'Invalid fields' => $validationViolations
        ], Response::HTTP_BAD_REQUEST);
    }

    public static function paymentResultDependentResponse(bool $paymentResult): JsonResponse
    {
        return new JsonResponse([
            'message' => $paymentResult ? 'Payment successful' : 'Payment failed'
        ], $paymentResult ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    public static function calculateResponse(int $price): JsonResponse
    {
        return new JsonResponse([
            'price' => $price
        ]);
    }

    public static function wrongTaxNumber()
    {
        return new JsonResponse([
            'message' => 'Wrong tax number was sent'
        ], Response::HTTP_BAD_REQUEST);
    }
}
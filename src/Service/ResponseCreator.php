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
}
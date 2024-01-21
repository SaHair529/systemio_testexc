<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestsValidator
{
    private Choice $productConstraints;
    private Regex $taxNumberConstraints;
    private NotBlank $couponCodeConstraints;
    private Choice $paymentProcessorConstraints;

    public function __construct(private ValidatorInterface $validator)
    {
        $this->productConstraints = new Choice([
            'choices' => [1, 2, 3],
            'message' => 'Возможные значения: 1 (Iphone), 2 (Наушники), 3 (Чехол)'
        ]);

        $this->taxNumberConstraints = new Regex([
            'pattern' => '/^(DE\d{9}|IT\d{11}|GR\d{9}|FR[A-Z]{2}\d{11})$/',
            'message' => 'Неверный формат налогового номера'
        ]);

        $this->couponCodeConstraints = new NotBlank(['message' => 'Поле "couponCode" обязательно']);

        $this->paymentProcessorConstraints = new Choice([
            'choices' => ['paypal', 'stripe'],
            'message' => 'Доступные сервисы: paypal, stripe'
        ]);
    }

    public function validateCalculatePrice(array $requestData): array
    {
        $result = [];

        $violations = $this->validator->validate($requestData, new Collection([
            'product' => $this->productConstraints,
            'taxNumber' => $this->taxNumberConstraints,
            'couponCode' => $this->couponCodeConstraints
        ]));

        foreach ($violations as $violation) {
            $result[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $result;
    }
}
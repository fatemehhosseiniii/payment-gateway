<?php

namespace App\Infrastructure\Adapters;


interface PaymentInterface
{

    public function pay(int $amount): array;

    public function verify(array $data);
}

<?php

namespace App\Services\Payments;


interface PaymentInterface
{

    public function pay(int $amount): array;

    public function verify(array $data);
}

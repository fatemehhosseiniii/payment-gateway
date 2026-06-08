<?php

namespace App\Infrastructure\Adapters;


use App\Builders\PaymentResponse\ErrorPaymentResponse;
use App\Builders\PaymentResponse\SuccessPaymentResponse;
use App\Builders\PaymentResponse\SuccessVerifyResponse;

interface PaymentInterface
{

    public function pay(int $amount): SuccessPaymentResponse|ErrorPaymentResponse;

    public function verify(array $data): SuccessVerifyResponse|ErrorPaymentResponse;
}

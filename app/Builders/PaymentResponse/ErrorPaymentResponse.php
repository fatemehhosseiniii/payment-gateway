<?php

namespace App\Builders\PaymentResponse;


use App\Models\Payment\Transaction;

class ErrorPaymentResponse
{
    public string $status = 'error';
    public string $message;
    public array|Transaction $transaction = [];

    public function setMessage(string $message): ErrorPaymentResponse
    {
        $this->message = $message;
        return $this;
    }

    public function setTransaction(Transaction|array $transaction): ErrorPaymentResponse
    {
        $this->transaction = $transaction;
        return $this;
    }

}

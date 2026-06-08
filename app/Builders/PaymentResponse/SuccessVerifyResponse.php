<?php

namespace App\Builders\PaymentResponse;

use App\Models\Payment\Transaction;

class SuccessVerifyResponse
{
    public string $status = 'success';
    public int $refid;
    public int $transaction_id;
    public array|Transaction $transaction = [];
    public string $date;

    public function setRefid(int $refid): SuccessVerifyResponse
    {
        $this->refid = $refid;
        return $this;
    }

    public function setTransactionId(int $transaction_id): SuccessVerifyResponse
    {
        $this->transaction_id = $transaction_id;
        return $this;
    }

    public function setDate(string $date): SuccessVerifyResponse
    {
        $this->date = $date;
        return $this;
    }

    public function setTransaction(Transaction|array $transaction): SuccessVerifyResponse
    {
        $this->transaction = $transaction;
        return $this;
    }
}

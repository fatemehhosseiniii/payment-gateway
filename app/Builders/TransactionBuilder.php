<?php

namespace App\Builders;

use App\Enums\PayRequestStatus;
use App\Enums\TransactionStatus;
use App\Jobs\PayRequestStatusUpdateJob;
use App\Models\Payment\Transaction;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;

class TransactionBuilder
{
    public int $id;
    public int $pay_request_id;
    public int $amount;
    public string $trac_code;
    public int|null $refid = null;
    public int|null $transaction_id = null;
    public string|null $pay_date = null;
    public TransactionStatus $status = TransactionStatus::Pending;

    public function setPayRequestId(int $payRequestId): TransactionBuilder
    {
        $this->pay_request_id = $payRequestId;
        return $this;
    }

    public function setAmount(int $amount): TransactionBuilder
    {
        $this->amount = $amount;
        return $this;
    }


    public function pendingPay(string $tracCode): TransactionBuilder
    {
        $this->trac_code = $tracCode;
        return $this;
    }

    public function paid(int $refid, int $transaction_id, string $payDate): TransactionBuilder
    {
        $this->refid = $refid;
        $this->transaction_id = $transaction_id;
        $this->pay_date = Carbon::parse($payDate);
        $this->status = TransactionStatus::Success;

        return $this;
    }

    public function failed(): TransactionBuilder
    {
        $this->status = TransactionStatus::Fail;

        return $this;
    }

    public function getArray(): array
    {
        $data = [];
        if (!empty($this->pay_request_id))
            $data["pay_request_id"] = $this->pay_request_id;
        if (!empty($this->amount))
            $data["amount"] = $this->amount;
        if (!empty($this->trac_code))
            $data["trac_code"] = $this->trac_code;
        if (!empty($this->refid))
            $data["refid"] = $this->refid;
        if (!empty($this->transaction_id))
            $data["transaction_id"] = $this->transaction_id;
        if (!empty($this->pay_date))
            $data["pay_date"] = $this->pay_date;
        if (!empty($this->status))
            $data["status"] = $this->status;

        return $data;
    }

    public function build(int|null $id = null): Transaction
    {
        //todo: NEED REFACTOR
        //make
        $transaction = Transaction::updateOrCreate(['id' => $id ?? null], $this->getArray());

        //call Job for Reset pay request Status
        if ($this->status === TransactionStatus::Success) {
//            if ($transaction->payRequest->remaining_amount - $transaction->amount <= 0)
//                PayRequestStatusUpdateJob::dispatch($transaction->payRequest);

            $transaction->payRequest()->update([
                'remaining_amount' => $transaction->payRequest->remaining_amount - $transaction->amount,
                'status' => $transaction->payRequest->remaining_amount - $transaction->amount <= 0 ? PayRequestStatus::Success : PayRequestStatus::PartialFail
            ]);
        }


        return $transaction;
    }
}

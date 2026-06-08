<?php

namespace App\Listeners;

use App\Enums\PayRequestStatus;
use App\Enums\TransactionStatus;
use App\Events\PayRequestProcessed;
use App\Repositories\PayRequestRepository;

class UpdatePayRequestStatus
{
    private PayRequestRepository $payRequestRepository;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->payRequestRepository = new PayRequestRepository();
    }


    /**
     * Handle the event.
     */
    public function handle(PayRequestProcessed $event): void
    {
        //find pay request
        $payRequest = $event->payRequest;
        //Load Transactions
        $payRequest->load('transactions');
        $transactionStatuses = $payRequest->transactions->pluck('status')->toArray();

        $dataUpdated = [];
        //check fined and Status
        if ($payRequest) {

            if ($payRequest->transactions->where('status', TransactionStatus::Success)->sum('amount') === $payRequest->amount) {
                $dataUpdated['status'] = PayRequestStatus::Success;
                $dataUpdated['remaining_amount'] = 0;
            } elseif (in_array(TransactionStatus::Success, $transactionStatuses) && (in_array(TransactionStatus::Fail, $transactionStatuses) || in_array(TransactionStatus::Expired, $transactionStatuses)))
                $dataUpdated['status'] = PayRequestStatus::PartialFail;
            elseif (in_array(TransactionStatus::Fail, $transactionStatuses))
                $dataUpdated['status'] = PayRequestStatus::Fail;
            elseif (in_array(TransactionStatus::Expired, $transactionStatuses))
                $dataUpdated['status'] = PayRequestStatus::Expired;


            $this->payRequestRepository->update($payRequest, $dataUpdated);
        }

    }
}

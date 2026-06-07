<?php

namespace App\Jobs;

use App\Models\Payment\PayRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PayRequestStatusUpdateJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public PayRequest $payRequest)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //get all Transactions
        $transactions = $this->payRequest->transactions;


        foreach ($transactions as $transaction) {

        }
    }
}

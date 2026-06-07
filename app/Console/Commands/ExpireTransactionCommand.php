<?php

namespace App\Console\Commands;

use App\Enums\TransactionStatus;
use App\Models\Payment\Transaction;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:expire-transaction-command')]
#[Description('Checking a stateless transaction and canceling it after a specified time')]
class ExpireTransactionCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //Find pending fail Transactions
        Transaction::query()
            ->where('created_at', '<=', now()->subMinutes(10))
            ->where('status', TransactionStatus::Pending)
            ->update(['status' => TransactionStatus::Expired]);
    }
}

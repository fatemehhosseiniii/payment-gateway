<?php

namespace App\Repositories;

use App\Enums\TransactionStatus;
use App\Models\Payment\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository
{

    public function find(string $key, string $value): Model|null
    {
        return Transaction::where($key, $value)->where('status', TransactionStatus::Pending)->first();
    }

    public function create(array $data): Transaction
    {
        $transaction = Transaction::create($data);
        $transaction->refresh();
        return $transaction;
    }

    public function update($transaction, array $data): Transaction
    {
        $transaction->update($data);

        $transaction->refresh();
        return $transaction;
    }

}

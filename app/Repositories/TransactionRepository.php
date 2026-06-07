<?php

namespace App\Repositories;

use App\Models\Payment\Transaction;
use Illuminate\Database\Eloquent\Model;

class TransactionRepository
{

    public function find(string $key, string $value): Model
    {
        return Transaction::where($key, $value)->first();
    }

    public function create(array $data): Transaction
    {
        $transaction = Transaction::create($data);
        $transaction->refresh();
        return $transaction;
    }
    public function update($transaction,array $data): Transaction
    {
        $transaction->update($data);

        $transaction->refresh();
        return $transaction;
    }

}

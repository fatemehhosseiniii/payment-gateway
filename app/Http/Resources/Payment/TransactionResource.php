<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transaction = $this->resource;

        return [
            'amount'=>$transaction->amount,
            'transaction_id'=>$transaction->transaction_id,
            'pay_date'=>$transaction->pay_date,
            'status'=>$transaction->status->toArray(),
            'created_at'=>$transaction->created_at,
        ];
    }
}

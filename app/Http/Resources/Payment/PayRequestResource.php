<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payReq = $this->resource;

        return [
            'code' => $payReq->code,
            'gateway' => $this->whenLoaded('gateway', fn() => $payReq->gateway->title),
            'amount' => $payReq->amount,
            'order_code' => $payReq->order_code,

            'created_at' => $payReq->created_at,
            'status' => $payReq->status->toArray(),

            'transactions' => $this->whenLoaded('transactions', fn() => $payReq->transactions->sortByDesc('created_at')->toResourceCollection()),
        ];
    }
}

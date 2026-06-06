<?php

namespace App\Http\Resources;

use App\Services\ArrayCrypt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $gateway = $this->resource;
        $simple = $this->additional['simple'] ?? false;


        return [
            'id' => $this->when(!$simple, $gateway->id),
            'title' => $gateway->title,
            'key' => $gateway->key,
//            'params' => $gateway->params,
            'params' => $this->when(!$simple, app(ArrayCrypt::class)->decrypt($gateway->params)),
            'is_active' => $this->when(!$simple, $gateway->is_active),
            'updated_at' => $this->when(!$simple, $gateway->updated_at),
        ];
    }
}

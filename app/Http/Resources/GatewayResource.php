<?php

namespace App\Http\Resources;

use App\Services\ArrayCrypt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GatewayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $gateway = $this->resource;

        return [
            'id' => $gateway->id,
            'title' => $gateway->title,
            'key' => $gateway->key,
//            'params' => $gateway->params,
            'params' => app(ArrayCrypt::class)->decrypt($gateway->params),
            'is_active' => $gateway->is_active,
            'updated_at' => $gateway->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $value=$this->resource;
        return [
            'current_page' => $value->currentPage(),
            'per_page' => $value->perPage(),
            'total' => $value->total(),
            'last_page' => $value->lastPage(),
            'from' => $value->firstItem(),
            'to' => $value->lastItem(),
        ];
    }
}

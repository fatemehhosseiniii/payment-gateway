<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GatewayCollection extends ResourceCollection
{

    public function toArray($request){
        return $this->resource->map(function($gateway){
           return $gateway->toResource()->additional($this->additional);
        });
    }

}

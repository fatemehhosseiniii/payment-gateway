<?php

namespace App\Services\Payments;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;

class GatewayResolve
{

    /**
     * Find Gateway Adapter Class for Connect to payment gate
     * @param $gatewayKey
     * @return Repository|Application|mixed|object|null
     * @throws \Exception
     */
    public function resolve($gatewayKey): mixed
    {
        $service = config('gateways.' . $gatewayKey);

        if (!$service) {
            throw new \Exception(__('payment.gateway-not-found', ['gateway' => $gatewayKey]));
        }

        return app($service);
    }

}

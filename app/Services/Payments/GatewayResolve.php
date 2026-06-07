<?php

namespace App\Services\Payments;

class GatewayResolve
{

    public function resolve($gatewayKey)
    {
        $service = config('gateways.' . $gatewayKey);

        if (!$service) {
            throw new \Exception(__('payment.gateway-not-found', ['gateway' => $gatewayKey]));
        }

        return app($service);
    }

}

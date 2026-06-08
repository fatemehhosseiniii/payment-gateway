<?php

namespace App\Builders\PaymentResponse;

class SuccessPaymentResponse
{
    public string $status = 'success';
    public string $redirect_route;
    public string $trac_code;

    public function setRedirectRoute(string $redirectRoute): SuccessPaymentResponse
    {
        $this->redirect_route = $redirectRoute;
        return $this;
    }

    public function setTracCode(string $tracCode): SuccessPaymentResponse
    {
        $this->trac_code = $tracCode;
        return $this;
    }


}

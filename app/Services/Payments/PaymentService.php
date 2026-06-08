<?php

namespace App\Services\Payments;

use App\Http\Resources\Payment\TransactionResource;
use App\Models\Payment\PayRequest;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;

class PaymentService
{

    private PayRequest|null $payRequest = null;
    private int|null $payAmount = null;
    private PaymentDatabaseLogic $paymentDatabaseLogic;

    public function __construct()
    {
        $this->paymentDatabaseLogic = new PaymentDatabaseLogic();
    }

    /**
     * make Data and Return redirect route for pyment
     * @param array $data
     * @return array
     * @throws BindingResolutionException
     * @throws CircularDependencyException
     * @throws Exception
     */
    public function pay(array $data): array
    {
        //get payment service need
        $payService = app(GatewayResolve::class)->resolve($data['gateway_key']);

        //Create pay Request
        if (!$this->payRequest)
            $this->payRequest = $this->paymentDatabaseLogic->createRequest($data);

        $gateway = $this->payRequest->gateway;
        if ($gateway && !empty($gateway->max_amount) && $this->payRequest->remaining_amount >= $gateway->max_amount)
            $this->payAmount = $gateway->max_amount;
        else
            $this->payAmount = $this->payRequest->remaining_amount;

        //Connect pay gateway
        $result = $payService->pay($this->payAmount);

        //save Transaction
        if (!empty($result['trac_code']))
            $this->paymentDatabaseLogic->createTransaction($this->payRequest, $this->payAmount, $result['trac_code']);

        if ($this->payRequest->remaining_amount > 0)
            $result['detail']['remaining_amount'] = $this->payRequest->remaining_amount;

        return $result;
    }


    /**
     * Verify Pay request
     * @param string $gateway
     * @param array $data
     * @return array
     * @throws BindingResolutionException
     * @throws CircularDependencyException
     */
    public function verify(string $gateway, array $data): array
    {
        $payService = app(GatewayResolve::class)->resolve($gateway);

        //send Request to verify
        $result = $payService->verify($data);

        if (empty($result['transaction']))
            throw new Exception(__('payment.transaction-not-found'));

        //Update Transaction Status
        list($transactionVerify, $transaction) = $this->paymentDatabaseLogic->verifyTransaction($result, $result['transaction']);

        //reset variables
        $this->payRequest = $transaction->payRequest;
        $this->payAmount = $transaction->amount;

        if (!$transactionVerify || ($transactionVerify && $this->payRequest->remaining_amount > 0))
            return $this->pay(['gateway_key' => $transaction->payRequest->gateway->key]);

        //set transaction resource detail
        $result['transaction']->refresh();
        $result['detail']['transaction'] = (new TransactionResource($result['transaction']))->resolve();
        unset($result['transaction']);

        return $result;
    }


}

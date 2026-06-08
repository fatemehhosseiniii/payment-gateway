<?php

namespace App\Services\Payments;

use App\Builders\TransactionBuilder;
use App\Models\Payment\PayRequest;
use App\Models\Payment\Transaction;
use App\Repositories\GatewayRepository;
use App\Repositories\PayRequestRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentDatabaseLogic
{

    /**
     * Create new pay-request
     * @param array $data
     * @return PayRequest
     * @throws Exception
     */
    public function createRequest(array $data): PayRequest
    {
        $payRequestRepository = new PayRequestRepository();
        $gatewayRepository = new GatewayRepository();

        try {
            //Find Gateway
            $gateway = $gatewayRepository->find('key', $data['gateway_key']);
            $data['gateway_id'] = $gateway->id;
            $data['remaining_amount'] = $data['amount'];

            //save main request
            return $payRequestRepository->create($data);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return throw new Exception(__('payment.gateway-not-found', ['gateway' => $data['gateway_key']]));
        }
    }

    /**
     * Create new pending transaction
     * @param $payRequest
     * @param $payAmount
     * @param $tracCode
     * @return void
     */
    public function createTransaction($payRequest, $payAmount, $tracCode): void
    {
        (new TransactionBuilder())->setPayRequestId($payRequest->id)
            ->setAmount($payAmount)
            ->pendingPay($tracCode)
            ->build();
    }


    /**
     * Update Transaction Status (Failed or Success)
     * @param $verifyResult
     * @param Transaction $transaction
     * @return array
     */
    public function verifyTransaction($verifyResult, Transaction $transaction): array
    {
        $transactionBuilder = new TransactionBuilder();

        $resultVerify = true;

        //check verify status
        if (!empty($verifyResult['status']) && $verifyResult['status'] == 'error') {
            $transactionBuilder->failed();
            $resultVerify = false;
        } else
            $transactionBuilder->paid($verifyResult['refid'], $verifyResult['transaction_id'], $verifyResult['date']);

        $transactionBuilder->build($transaction->id);

        $transaction->load('payRequest.gateway');

        return [$resultVerify, $transaction];
    }


}

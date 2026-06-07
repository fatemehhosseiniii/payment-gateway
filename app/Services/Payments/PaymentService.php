<?php

namespace App\Services\Payments;


use App\Enums\PayRequestStatus;
use App\Enums\TransactionStatus;
use App\Models\Payment\PayRequest;
use App\Models\Payment\Transaction;
use App\Repositories\GatewayRepository;
use App\Repositories\PayRequestRepository;
use App\Repositories\TransactionRepository;
use App\Services\Payments\Gateways\ShepaService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public TransactionRepository $transactionRepository;

    public function __construct()
    {
        $this->transactionRepository = new TransactionRepository();
    }

    public function pay(array $data): array
    {

        $payService = app(GatewayResolve::class)->resolve($data['gateway_key']);

        $payRequest = $this->createRequest($data);

        $result = $payService->pay($payRequest->amount - $payRequest->remaining_amount);

        //save Transaction
        if (!empty($result['trac_code']))
            $this->createTransaction($payRequest, $payRequest->amount - $payRequest->remaining_amount, $result['trac_code']);

        if ($payRequest->remaining_amount > 0)
            $result['detail']['have_more_transaction'] = true;

        return $result;
    }

    public function verify(string $gateway, array $data): array
    {
        $payService = app(GatewayResolve::class)->resolve($gateway);

        $result = $payService->verify($data);

        if (!empty($result['status']) && $result['status'] == 'error') {
            $transaction = $this->failTransaction($result['transaction']);

            $payService = app(GatewayResolve::class)->resolve($transaction->payRequest->gateway->key);

            $result = $payService->pay($transaction->amount);

            //save Transaction
            if (!empty($result['trac_code']))
                $this->createTransaction($transaction->payRequest, $transaction->amount, $result['trac_code']);

            return $result;
        }
        //save Transaction
        if (!empty($result['refid']))
            $transaction = $this->verifyTransaction($data['token'], $result);

        //checked pay all Amount
        $transaction->load('payRequest.gateway');

        if ($transaction->payRequest->remaining_amount > 0) {

            $payService = app(GatewayResolve::class)->resolve($transaction->payRequest->gateway->key);

            if (!empty($transaction->payRequest->gateway->max_amount) && $transaction->payRequest->remaining_amount >= $transaction->payRequest->gateway->max_amount)
                $payAmount = $transaction->payRequest->remaining_amount - $transaction->payRequest->gateway->max_amount;
            else
                $payAmount = $transaction->payRequest->remaining_amount;

            $transaction->payRequest()->update([
                'remaining_amount' => $transaction->payRequest->remaining_amount - $payAmount
            ]);

            $result = $payService->pay($payAmount);

            //save Transaction
            if (!empty($result['trac_code']))
                $this->createTransaction($transaction->payRequest, $payAmount, $result['trac_code']);

            return $result;

        } else
            $transaction->payRequest()->update([
                'remaining_amount' => 0,
                'status' => PayRequestStatus::Success
            ]);


        return $result;
    }

    private function createRequest(array $data)
    {
        $payRequestRepository = new PayRequestRepository();
        $gatewayRepository = new GatewayRepository();

        try {
            //Find Gateway
            $gateway = $gatewayRepository->find('key', $data['gateway_key']);
            if (!empty($gateway)) {
                unset($data['gateway_key']);
                $data['gateway_id'] = $gateway->id;
            }

            //check max amount gateway handled
            //todo: move Other function
            if (!empty($gateway->max_amount) && $data['amount'] >= $gateway->max_amount)
                $data['remaining_amount'] = $data['amount'] - $gateway->max_amount;


            //save main request
            return $payRequestRepository->create($data);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return throw new \Exception(__('payment.gateway-not-found', ['gateway' => $data['gateway_key']]));
        }

    }

    private function createTransaction(PayRequest $payRequest, $payAmount, $tracCode): void
    {
        //todo: Can use Builder
        $this->transactionRepository->create([
            'pay_request_id' => $payRequest->id,
            'amount' => $payAmount,
            'trac_code' => $tracCode,
        ]);
    }

    private function verifyTransaction($tracCode, $verifyData): Transaction
    {
        $transaction = $this->transactionRepository->find('trac_code', $tracCode);
        if (!$transaction)
            throw new \Exception(__('payment.transaction-not-found'));

        $this->transactionRepository->update($transaction, [
            'refid' => $verifyData['refid'],
            'transaction_id' => $verifyData['transaction_id'],
            'pay_date' => Carbon::parse($verifyData['date']),
            'pay_status' => 'success',
            'status' => TransactionStatus::Success,
        ]);

        return $transaction;

    }

    private function failTransaction($transaction): Transaction
    {
        if (!$transaction)
            throw new \Exception(__('payment.transaction-not-found'));

        $this->transactionRepository->update($transaction, [
            'pay_status' => 'failed',
            'status' => TransactionStatus::Fail,
        ]);

        return $transaction;

    }

}

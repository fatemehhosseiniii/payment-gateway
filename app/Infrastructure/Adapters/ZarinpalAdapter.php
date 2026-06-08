<?php

namespace App\Infrastructure\Adapters;

use App\Builders\PaymentResponse\ErrorPaymentResponse;
use App\Builders\PaymentResponse\SuccessPaymentResponse;
use App\Builders\PaymentResponse\SuccessVerifyResponse;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use NasrinRezaei45\Shepacom\ShepaFacade;

class ZarinpalAdapter implements PaymentInterface
{
    public string $driver;
    public string $requestUrl;
    public string $paymentUrl;
    public string $verifyUrl;

    public function __construct()
    {
        $this->driver = config('services.zarinpal.driver');
        $this->requestUrl = config('services.zarinpal.' . $this->driver . '-request-url');
        $this->paymentUrl = config('services.zarinpal.' . $this->driver . '-pay-url');
        $this->verifyUrl = config('services.zarinpal.' . $this->driver . '-verify-url');
    }

    /**
     * @param int $amount
     * @return SuccessPaymentResponse|ErrorPaymentResponse
     */
    public function pay(int $amount): SuccessPaymentResponse|ErrorPaymentResponse
    {
        try {

            $client = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->requestUrl, [
                'merchant_id' => config('services.zarinpal.merchant-id'),
                'amount' => $amount,
                'callback_url' => config('services.zarinpal.callback-url'),
                'description' => '-'
            ]);

            if (!$client->successful() || empty($client->json()['data']['authority'])) {
                return (new ErrorPaymentResponse())->setMessage($client->json()['errors']['message'] ?? __('payment.gateway-error'));
            }

            $authority = $client->json()['data']['authority'];

            $paymentRoute = $this->paymentUrl . '/' . $authority;

            return (new SuccessPaymentResponse())
                ->setTracCode($authority)
                ->setRedirectRoute($paymentRoute);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return (new ErrorPaymentResponse())->setMessage($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @return SuccessVerifyResponse|ErrorPaymentResponse
     * @throws ConnectionException
     */
    public function verify(array $data): SuccessVerifyResponse|ErrorPaymentResponse
    {
        //find transaction
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->find('trac_code', $data['Authority'] ?? '');


        if (isset($data['Status']) && ($data['Status'] == "OK") && $transaction) {

            $client = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($this->verifyUrl, [
                'merchant_id' => config('services.zarinpal.merchant-id'),
                'amount' => $transaction->amount,
                'authority' => $transaction->trac_code
            ]);

            if (!$client->successful())
                return (new ErrorPaymentResponse())->setMessage(__('payment.payment-invalid'))->setTransaction($transaction);

            $response = $client->json()['data'] ?? [];

            if (!empty($response['message']) && $response['message'] == 'Paid')
                return (new SuccessVerifyResponse())
                    ->setRefid($response['ref_id'])
                    ->setTransactionId($response['ref_id'])
                    ->setDate(now()->toDateTimeString())
                    ->setTransaction($transaction);
            else
                return (new ErrorPaymentResponse())->setMessage(__('payment.payment-invalid'))->setTransaction($transaction);

        } else {
            return (new ErrorPaymentResponse())->setMessage(__('payment.payment-invalid'))->setTransaction($transaction ?? []);
        }

    }

}

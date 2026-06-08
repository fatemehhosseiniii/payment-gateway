<?php

namespace App\Infrastructure\Adapters;

use App\Repositories\TransactionRepository;
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
     * @return array
     */
    public function pay(int $amount): array
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
                return ['status' => 'error', 'message' => $client->json()['errors']['message'] ?? __('payment.gateway-error')];
            }

            $authority = $client->json()['data']['authority'];

            $paymentRoute = $this->paymentUrl . '/' . $authority;

            return ['status' => 'success', 'redirect_route' => $paymentRoute, 'trac_code' => $authority];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return ['status' => 'error', 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function verify(array $data): array
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
                return ['status' => 'error', 'message' => 'Invalid payment.', 'transaction' => $transaction];
            $response = $client->json()['data'] ?? [];

            if (!empty($response['message']) && $response['message'] == 'Paid')
                return [
                    'status' => 'success',
                    'refid' => $response['ref_id'],
                    'transaction_id' => $response['ref_id'],
                    'date' => now()->toDateTimeString(),

                    'transaction' => $transaction
                ];
            else
                return ['status' => 'error', 'message' => $result['message'] ?? 'Invalid payment.', 'transaction' => $transaction];

        } else {
            return ['status' => 'error', 'message' => 'Invalid payment.', 'transaction' => $transaction ?? null];
        }

    }

}

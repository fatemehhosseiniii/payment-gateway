<?php

namespace App\Services\Payments\Gateways;

use App\Repositories\TransactionRepository;
use App\Services\Payments\PaymentInterface;
use Illuminate\Support\Facades\Log;
use NasrinRezaei45\Shepacom\ShepaFacade;

class ShepaService implements PaymentInterface
{
    public function pay(int $amount): array
    {
        try {
            $result = ShepaFacade::send($amount, '', '', '');

            $tracCode = explode('/', $result);
            $tracCode = $tracCode[count($tracCode) - 1];

            return ['status' => 'success', 'redirect_route' => $result, 'trac_code' => $tracCode];
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return ['status' => 'error', 'message' => $exception->getMessage()];
        }
    }

    public function verify(array $data)
    {
        //find transaction
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->find('trac_code', $data['token']);


        if (isset($data['status']) && ($data['status'] == "success") && $transaction) {

            $result = ShepaFacade::verify($transaction->trac_code, $transaction->amount);

            if (!empty($result['refid']))
                return $result;
            else
                return ['status' => 'error', 'message' => $result['message'] ?? 'Invalid payment.', 'transaction' => $transaction];

        } else {
            return ['status' => 'error', 'message' => 'Invalid payment.', 'transaction' => $transaction ?? null];
        }

    }

}

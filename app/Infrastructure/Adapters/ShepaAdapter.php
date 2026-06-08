<?php

namespace App\Infrastructure\Adapters;

use App\Builders\PaymentResponse\ErrorPaymentResponse;
use App\Builders\PaymentResponse\SuccessPaymentResponse;
use App\Builders\PaymentResponse\SuccessVerifyResponse;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Log;
use NasrinRezaei45\Shepacom\ShepaFacade;

class ShepaAdapter implements PaymentInterface
{
    /**
     * @param int $amount
     * @return SuccessPaymentResponse|ErrorPaymentResponse
     */
    public function pay(int $amount): SuccessPaymentResponse|ErrorPaymentResponse
    {
        try {
            $result = ShepaFacade::send($amount, '', '', '');

            $tracCode = explode('/', $result);
            $tracCode = $tracCode[count($tracCode) - 1];

            return (new SuccessPaymentResponse())
                ->setTracCode($tracCode)
                ->setRedirectRoute($result);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return (new ErrorPaymentResponse())->setMessage($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @return ErrorPaymentResponse
     */
    public function verify(array $data): SuccessVerifyResponse|ErrorPaymentResponse
    {
        //find transaction
        $transactionRepository = new TransactionRepository();
        $transaction = $transactionRepository->find('trac_code', $data['token'] ?? '');


        if (isset($data['status']) && ($data['status'] == "success") && $transaction) {

            $result = ShepaFacade::verify($transaction->trac_code, $transaction->amount);

            if (!empty($result['refid'])){
                return (new SuccessVerifyResponse())
                    ->setRefid($result['refid'])
                    ->setTransactionId($result['transaction_id'])
                    ->setDate($result['date'])
                    ->setTransaction($transaction);
            }
            else
                return (new ErrorPaymentResponse())->setMessage(__('payment.payment-invalid'))->setTransaction($transaction ?? []);

        } else {
            return (new ErrorPaymentResponse())->setMessage(__('payment.payment-invalid'))->setTransaction($transaction ?? []);
        }

    }

}

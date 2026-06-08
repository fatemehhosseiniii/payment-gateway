<?php

namespace App\Repositories;

use App\Models\Payment\PayRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PayRequestRepository
{

    public function find(string $key, string $value): PayRequest
    {
        return PayRequest::where($key, $value)->first();
    }

    /**
     * load Pay requests list with filtering
     * @param array $filterData
     * @return LengthAwarePaginator
     */
    public function getList(array $filterData): LengthAwarePaginator
    {
        $payRequests = PayRequest::query()->with('gateway');

        //apply filter values
        if (!empty($filterData['order_code'])) {
            $payRequests->whereLike('order_code', '%' . $filterData['order_code'] . '%');
        }
        if (isset($filterData['status'])) {
            $payRequests->where('status', $filterData['status']);
        }

        return $payRequests->orderByDesc('created_at')->paginate(config('setting.paginate-per-page'));
    }


    public function create(array $data): PayRequest
    {
        $payRequest = PayRequest::create($data);
        $payRequest->refresh();
        return $payRequest;
    }

    /**
     * Update Gateway Values
     * @param PayRequest|Model $model
     * @param array $data
     * @return PayRequest
     */
    public function update(PayRequest|Model $model, array $data): PayRequest
    {
        $model->update($data);
        $model->refresh();
        return $model;
    }

}

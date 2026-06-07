<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Paymeny\PayRequestRequest;
use App\Services\Payments\PaymentService;
use App\Services\Response;
use Illuminate\Http\Request;

class PayRequestController extends Controller
{
    public function store(PayRequestRequest $request)
    {
        //call payment Service
        $service = new PaymentService();
        $result = $service->pay($request->validated());

        //return Result
        if (isset($result['status']) && $result['status'] == 'success') {
            return Response::success(['redirect_route' => $result['redirect_route']] + ($result['detail'] ?? []));
        }

        return Response::error($result['message'] ?? __('errors.500'));
    }

    public function verify(Request $request, $gateway)
    {
        //call payment Service
        $service = new PaymentService();
        $result = $service->verify($gateway, $request->all());

        return [$result,'susses'=>'here'];
        //return Result
        if (isset($result['status']) && $result['status'] == 'success') {

            if (!empty($result['redirect_route']))
                return Response::success(['redirect_route' => $result['redirect_route']] + ($result['detail'] ?? []));
            else
                return Response::success();
        }

        return Response::error($result['message'] ?? __('errors.500'));
    }
}

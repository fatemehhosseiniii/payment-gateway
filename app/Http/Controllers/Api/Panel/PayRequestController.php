<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Panel\PayRequestRequest;
use App\Http\Resources\PaginateResource;
use App\Models\Payment\PayRequest;
use App\Repositories\PayRequestRepository;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use Throwable;

class PayRequestController extends Controller
{

    public function __construct(public PayRequestRepository $payRequestRepository)
    {
        //
    }


    /**
     * load gateways List with applying filter
     * @param PayRequestRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function index(PayRequestRequest $request): JsonResponse
    {
        $payRequests = $this->payRequestRepository->getList($request->validated());
        return Response::success([
            'pay_requests' => $payRequests->toResourceCollection(),
            'paginate' => new PaginateResource($payRequests)
        ]);
    }

    /**
     * Response Pay Request detail
     * @param PayRequest $payRequest
     * @return JsonResponse
     */
    public function show(PayRequest $payRequest): JsonResponse
    {
        $payRequest->load('transactions', 'gateway');
        return Response::success(['pay_request' => $payRequest->toResource()]);
    }
}

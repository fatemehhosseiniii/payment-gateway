<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Paymeny\PayRequestRequest;
use App\Services\Payments\PaymentService;
use App\Services\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PayRequestController extends Controller
{

    /**
     * @param PayRequestRequest $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws CircularDependencyException
     */
    #[OA\Post(
        path: '/api/payment/pay-request',
        description: 'Pay request',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['amount', 'order_code', 'gateway_key'],
                properties: [
                    new OA\Property(
                        property: 'amount',
                        type: 'integer',
                        example: 1500000
                    ),
                    new OA\Property(
                        property: 'order_code',
                        type: 'string',
                        example: '124200'
                    ),
                    new OA\Property(
                        property: 'gateway_key',
                        type: 'string',
                        example: 'shepa'
                    ),
                ]
            )
        ),
        tags: ['Payment'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'success'
                        ),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(
                                    property: 'redirect_route',
                                    type: 'string',
                                    format: 'uri',
                                    example: 'https://sandbox.shepa.com/v1/dc3006...............16a9235d'
                                ),
                                new OA\Property(
                                    property: 'remaining_amount',
                                    type: 'integer',
                                    example: 1000000
                                ),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),

            new OA\Response(
                response: 422,
                description: 'Validation Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'error'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'The amount field is required.'
                        ),
                    ]
                )
            ),

            new OA\Response(
                response: 404,
                description: 'Gateway Not Found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'status',
                            type: 'string',
                            example: 'error'
                        ),
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Gateway not found.'
                        ),
                    ]
                )
            ),
        ]
    )]
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

        //return Result
        if (!empty($result['refid']) || !empty($result['status'])) {

            if (!empty($result['redirect_route']))
                return Response::success(['redirect_route' => $result['redirect_route']] + ($result['detail'] ?? []));
            else
                return Response::success($result['detail'] ?? []);
        }

        return Response::error($result['message'] ?? __('errors.500'));
    }
}

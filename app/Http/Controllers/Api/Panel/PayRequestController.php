<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Panel\PayRequestRequest;
use App\Http\Resources\PaginateResource;
use App\Models\Payment\PayRequest;
use App\Repositories\PayRequestRepository;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
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
    #[OA\Get(
        path: '/api/panel/pay-requests',
        description: "Pay requests list",
        security: [["bearerAuth" => []]],
        tags: ["Pay Requests"],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(
                    type: 'string'
                ),
                example: 'application/json'
            ),
            new OA\Parameter(
                name: 'order_code',
                in: 'query',
                schema: new OA\Schema(
                    type: 'string')
            ),
            new OA\Parameter(
                name: 'status',
                in: 'query',
                schema: new OA\Schema(
                    type: 'string'
                )
            ),
        ], responses: [
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
                                property: 'pay_requests',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(
                                            property: 'code',
                                            type: 'integer',
                                            example: 102
                                        ),
                                        new OA\Property(
                                            property: 'gateway',
                                            type: 'string',
                                            example: 'Shepa'
                                        ),
                                        new OA\Property(
                                            property: 'amount',
                                            type: 'integer',
                                            example: 1500000
                                        ),
                                        new OA\Property(
                                            property: 'order_code',
                                            type: 'integer',
                                            example: 124211
                                        ),
                                        new OA\Property(
                                            property: 'created_at',
                                            type: 'string',
                                            format: 'date-time'
                                        ),
                                        new OA\Property(
                                            property: 'status',
                                            properties: [
                                                new OA\Property(
                                                    property: 'key',
                                                    type: 'string',
                                                    example: 'Pending'
                                                ),
                                                new OA\Property(
                                                    property: 'label',
                                                    type: 'string',
                                                    example: 'درانتظار پرداخت'
                                                ),
                                            ],
                                            type: 'object'
                                        ),
                                    ],
                                    type: 'object'
                                )
                            ),

                            new OA\Property(
                                property: 'paginate',
                                properties: [
                                    new OA\Property(
                                        property: 'current_page',
                                        type: 'integer',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'per_page',
                                        type: 'integer',
                                        example: 10
                                    ),
                                    new OA\Property(
                                        property: 'total',
                                        type: 'integer',
                                        example: 2
                                    ),
                                    new OA\Property(
                                        property: 'last_page',
                                        type: 'integer',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'from',
                                        type: 'integer',
                                        example: 1
                                    ),
                                    new OA\Property(
                                        property: 'to',
                                        type: 'integer',
                                        example: 2
                                    ),
                                ],
                                type: 'object'
                            ),
                        ],
                        type: 'object'
                    ),
                ]
            )
        ),

        new OA\Response(
            response: 401,
            description: 'Unauthenticated',
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
                        example: 'Unauthenticated.'
                    ),
                ]
            )
        ),
    ]
    )]
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
    #[OA\GET(
        path: '/api/panel/pay-requests/{code}',
        description: 'View pay Request',
        security: [['bearerAuth' => []]],
        tags: ["Pay Requests"],
        parameters: [
            new OA\Parameter(
                name: 'code',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                ),
                example: 101
            ),
        ],

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
                                    property: 'pay_request',
                                    properties: [
                                        new OA\Property(
                                            property: 'code',
                                            type: 'integer',
                                            example: 102
                                        ),
                                        new OA\Property(
                                            property: 'gateway',
                                            type: 'string',
                                            example: 'Shepa'
                                        ),
                                        new OA\Property(
                                            property: 'amount',
                                            type: 'integer',
                                            example: 1500000
                                        ),
                                        new OA\Property(
                                            property: 'remaining_amount',
                                            type: 'integer',
                                            example: 500000
                                        ),
                                        new OA\Property(
                                            property: 'order_code',
                                            type: 'integer',
                                            example: 124211
                                        ),
                                        new OA\Property(
                                            property: 'created_at',
                                            type: 'string',
                                            format: 'date-time'
                                        ),
                                        new OA\Property(
                                            property: 'status',
                                            properties: [
                                                new OA\Property(
                                                    property: 'key',
                                                    type: 'string',
                                                    example: 'Pending'
                                                ),
                                                new OA\Property(
                                                    property: 'label',
                                                    type: 'string',
                                                    example: 'درانتظار پرداخت'
                                                ),
                                            ],
                                            type: 'object'
                                        ),

                                        new OA\Property(
                                            property: 'transactions',
                                            type: 'array',
                                            items: new OA\Items(
                                                properties: [
                                                    new OA\Property(
                                                        property: 'amount',
                                                        type: 'integer',
                                                        example: 500000
                                                    ),
                                                    new OA\Property(
                                                        property: 'transaction_id',
                                                        type: 'string',
                                                        example: '32298'
                                                    ),
                                                    new OA\Property(
                                                        property: 'pay_date',
                                                        type: 'string',
                                                        example: '2026-06-07 12:14:11'
                                                    ),
                                                    new OA\Property(
                                                        property: 'status',
                                                        properties: [
                                                            new OA\Property(
                                                                property: 'key',
                                                                type: 'string',
                                                                example: 'Success'
                                                            ),
                                                            new OA\Property(
                                                                property: 'label',
                                                                type: 'string',
                                                                example: 'پرداخت موفق'
                                                            ),
                                                        ],
                                                        type: 'object'
                                                    ),
                                                    new OA\Property(
                                                        property: 'created_at',
                                                        type: 'string',
                                                        format: 'date-time'
                                                    ),
                                                ],
                                                type: 'object'
                                            )
                                        ),
                                    ],
                                    type: 'object'
                                ),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),

            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
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
                            example: 'Unauthenticated.'
                        ),
                    ]
                )
            ),

            new OA\Response(
                response: 404,
                description: 'Pay Request Not Found',
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
                            example: 'Pay request not found.'
                        ),
                    ]
                )
            ),
        ]
    )]
    public function show(PayRequest $payRequest): JsonResponse
    {
        $payRequest->load('transactions', 'gateway');
        return Response::success(['pay_request' => $payRequest->toResource()]);
    }
}

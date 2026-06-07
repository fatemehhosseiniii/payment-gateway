<?php

namespace App\Http\Controllers\Api\Panel;

use App\Enums\PayRequestStatus;
use App\Http\Controllers\Controller;
use App\Services\Response;
use OpenApi\Attributes as OA;

class PayRequestStatusController extends Controller
{
    #[OA\Get(
        path: '/api/panel/pay-request-statuses',
        description: "Statuses pay-requests",
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
                                    property: 'statuses',
                                    type: 'array',
                                    items: new OA\Items(
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
                                    )
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
    public function __invoke()
    {
        $statuses=collect(PayRequestStatus::cases())->map(fn($status)=>$status->toArray());

        return Response::success(['statuses'=>$statuses]);
    }
}

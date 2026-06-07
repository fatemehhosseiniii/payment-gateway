<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Controller;
use App\Repositories\GatewayRepository;
use App\Services\Response;
use OpenApi\Attributes as OA;

class GatewayController extends Controller
{
    public function __construct(private readonly GatewayRepository $gatewayRepository)
    {
        //
    }

    #[OA\Get(
        path: '/api/data/gateways',
        description: "Active Gateways list",
        tags: ['Payment'],
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
                                    property: 'gateways',
                                    type: 'array',
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(
                                                property: 'title',
                                                type: 'string',
                                                example: 'Shepa'
                                            ),
                                            new OA\Property(
                                                property: 'key',
                                                type: 'string',
                                                example: 'shepa'
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
        //load gateways list for selected by front
        $gateways=$this->gatewayRepository->getActiveList();

        return Response::success(['gateways'=>$gateways->toResourceCollection()->additional(['simple'=>true])]);
    }
}

<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Panel\GatewayRequest;
use App\Http\Resources\PaginateResource;
use App\Models\Gateway;
use App\Repositories\GatewayRepository;
use App\Services\Response;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Throwable;


class GatewayController extends Controller
{

    public function __construct(public GatewayRepository $gatewayRepository)
    {
        //
    }

    /**
     * load gateways List with applying filter
     * @param GatewayRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    #[OA\Get(
        path: '/api/panel/gateways',
        description: "Gateways list",
        security: [["bearerAuth" => []]],
        tags: ["Gateways"],
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
                name: 'title',
                in: 'query',
                schema: new OA\Schema(
                    type: 'string')
            ),
            new OA\Parameter(
                name: 'is_active',
                in: 'query',
                schema: new OA\Schema(
                    type: 'boolean'
                )
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
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'title', type: 'string', example: 'Shepa'),
                                            new OA\Property(property: 'key', type: 'string', example: 'shepa'),
                                            new OA\Property(property: 'params', type: 'array', items: new OA\Items(type: 'object')),
                                            new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                                        ],
                                        type: 'object'
                                    )
                                ),

                                new OA\Property(
                                    property: 'paginate',
                                    properties: [
                                        new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                        new OA\Property(property: 'per_page', type: 'integer', example: 10),
                                        new OA\Property(property: 'total', type: 'integer', example: 2),
                                        new OA\Property(property: 'last_page', type: 'integer', example: 1),
                                        new OA\Property(property: 'from', type: 'integer', example: 1),
                                        new OA\Property(property: 'to', type: 'integer', example: 2),
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
    public function index(GatewayRequest $request)
    {
        $gateways = $this->gatewayRepository->getList($request->validated());
        return Response::success([
            'gateways' => $gateways->toResourceCollection(),
            'paginate' => new PaginateResource($gateways)
        ]);
    }

    /**
     * Save New Gateway
     * @param GatewayRequest $request
     * @return JsonResponse
     */
    #[OA\Post(
        path: '/api/panel/gateways',
        description: 'Save New',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'key'],
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        example: 'شپا'
                    ),
                    new OA\Property(
                        property: 'key',
                        type: 'string',
                        example: 'shepa'
                    ),
                    new OA\Property(
                        property: 'params',
                        properties: [
                            new OA\Property(
                                property: 'key',
                                type: 'string',
                                example: 'testKey'
                            ),
                        ],
                        type: 'object'
                    ),
                ]
            )
        ),

        tags: ['Gateways'],

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
                                    property: 'gateway',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 3),
                                        new OA\Property(property: 'title', type: 'string', example: 'شپا'),
                                        new OA\Property(property: 'key', type: 'string', example: 'shepa'),
                                        new OA\Property(
                                            property: 'params',
                                            properties: [
                                                new OA\Property(
                                                    property: 'key',
                                                    type: 'string',
                                                    example: 'testKey'
                                                )
                                            ],
                                            type: 'object'
                                        ),
                                        new OA\Property(property: 'is_active', type: 'boolean', example: true),
                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
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
                            example: 'The key has already been taken.'
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
    public function store(GatewayRequest $request): JsonResponse
    {
        //save
        $gateway = $this->gatewayRepository->create($request->validated());
        //return Result Message
        return Response::success(['gateway' => $gateway->toResource()]);
    }

    /**
     * Update Gateway data
     * @param GatewayRequest $request
     * @param $gateway
     * @return JsonResponse
     */
    #[OA\Put(
        path: '/api/panel/gateways/{id}',
        description: 'Update Gateway',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'key'],
                properties: [
                    new OA\Property(
                        property: 'title',
                        type: 'string',
                        example: 'زرین پال'
                    ),
                    new OA\Property(
                        property: 'key',
                        type: 'string',
                        example: 'zarinpal'
                    ),
                    new OA\Property(
                        property: 'params',
                        properties: [
                            new OA\Property(
                                property: 'key',
                                type: 'string',
                                example: 'testKey'
                            ),
                        ],
                        type: 'object'
                    ),
                    new OA\Property(
                        property: 'is_active',
                        type: 'boolean',
                        example: false
                    ),
                ]
            )
        ),

        tags: ['Gateways'],

        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                ),
                example: 1
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
                                    property: 'gateway',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'title', type: 'string', example: 'زرین پال'),
                                        new OA\Property(property: 'key', type: 'string', example: 'zarinpal'),
                                        new OA\Property(
                                            property: 'params',
                                            properties: [
                                                new OA\Property(
                                                    property: 'key',
                                                    type: 'string',
                                                    example: 'testKey'
                                                )
                                            ],
                                            type: 'object'
                                        ),
                                        new OA\Property(property: 'is_active', type: 'boolean', example: false),
                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
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
                            example: 'The key has already been taken.'
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
    #[OA\Patch(
        path: '/api/panel/gateways/{id}',
        description: 'Update Status Gateway',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['is_active'],
                properties: [
                    new OA\Property(
                        property: 'is_active',
                        type: 'boolean',
                        example: false
                    ),
                ]
            )
        ),

        tags: ['Gateways'],

        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                ),
                example: 1
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
                                    property: 'gateway',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'title', type: 'string', example: 'زرین پال'),
                                        new OA\Property(property: 'key', type: 'string', example: 'zarinpal'),
                                        new OA\Property(
                                            property: 'params',
                                            properties: [
                                                new OA\Property(
                                                    property: 'key',
                                                    type: 'string',
                                                    example: 'testKey'
                                                )
                                            ],
                                            type: 'object'
                                        ),
                                        new OA\Property(property: 'is_active', type: 'boolean', example: false),
                                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
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
                            example: 'The key has already been taken.'
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
    public function update(GatewayRequest $request, Gateway $gateway)
    {
        //save
        $gateway = $this->gatewayRepository->update($gateway, $request->validated());
        //return Result Message
        return Response::success(['gateway' => $gateway->toResource()]);
    }

    /**
     * Remove Gateway (Soft-delete)
     * @param Gateway $gateway
     * @return JsonResponse
     */
    #[OA\Delete(
        path: '/api/panel/gateways/{id}',
        description: 'Remove Gateway',
        security: [['bearerAuth' => []]],
        tags: ['Gateways'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(
                    type: 'integer'
                ),
                example: 1
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
                            example: 'The key has already been taken.'
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
    public function destroy(Gateway $gateway)
    {
        //remove
        $this->gatewayRepository->delete($gateway);
        //return Result Message
        return Response::success([]);
    }
}

<?php

namespace App\OpenApi;


use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "My API"
)]

#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    bearerFormat: 'JWT',
    scheme: 'bearer'
)]
class OpenApi {}

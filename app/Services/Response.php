<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class Response
{

    public static function success(array $data = []): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], 200);
    }

    public static function error(string $message, int $code = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}

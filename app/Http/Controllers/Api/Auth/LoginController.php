<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use App\Services\Response;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0', title: 'Authenticated user')]
class LoginController extends Controller
{
    #[OA\Post(
        path: '/auth/login',
        description: "Login Admin for manage gateways",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(
                        property: 'email',
                        type: 'string',
                        format: 'email'
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        format: 'password'
                    ),
                ]
            )
        ),
        tags: ["Auth"],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Not allowed'),
        ]
    )]
    public function __invoke(LoginRequest $request)
    {
        $data = $request->validated();
        //Find User
        $user = User::where('email', $data['email'])->first();

        if ($user && Hash::check($data['password'], $user->password)) {
            //set login and create Token
            $user->tokens()->delete();
            $token = $user->createToken('panel');

            return Response::success(['token' => $token->plainTextToken]);
        }

        return Response::error(__('auth.failed'),403);
    }
}

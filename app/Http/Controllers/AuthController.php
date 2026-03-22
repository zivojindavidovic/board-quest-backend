<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginUserResource;
use App\Services\LoginUserService;
use App\Services\RegisterUserService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /*
     * Registers new user
     */
    public function register(RegisterRequest $request, RegisterUserService $registerUserService): JsonResponse
    {
        $registerUserService->execute($request->validated());

        return response()->json([], 201);
    }

    /*
     * Logins user
     */
    public function login(LoginRequest $request, LoginUserService $loginUserService): JsonResponse|LoginUserResource
    {
        $result = $loginUserService->execute($request->validated());

        if (! $result->success) {
            return response()->json([
                'success' => false,
                'message' => $result->error->message(),
                'error_code' => $result->error->value,
                'errors' => $result->error->errors(),
            ], $result->error->httpStatus());
        }

        return new LoginUserResource($result);
    }

    /*
     * Logouts user
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([], 204);
    }
}

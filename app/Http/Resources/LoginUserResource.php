<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token'      => $this->token,
            'token_type' => $this->tokenType,
            'expires_in' => $this->expiresIn,
            'user'       => $this->user,
        ];
    }

    public function toResponse($request): JsonResponse
    {
        $response = parent::toResponse($request);
        $data = ['success' => true] + $response->getData(true);
        $response->setData($data);

        return $response;
    }
}

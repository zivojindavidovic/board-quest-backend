<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListGameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    public function toResponse($request): JsonResponse
    {
        $response = parent::toResponse($request);
        $data = ['success' => true] + $response->getData(true);
        $response->setData($data);

        return $response;
    }
}

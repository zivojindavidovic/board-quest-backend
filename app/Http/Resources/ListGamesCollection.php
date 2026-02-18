<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListGamesCollection extends ResourceCollection
{
    public $collects = ListGamesResource::class;

    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        $currentPage = $paginated['current_page'];
        $lastPage = $paginated['last_page'];

        return [
            'meta' => [
                'current_page'     => $currentPage,
                'last_page'        => $lastPage,
                'next_page'        => $currentPage < $lastPage ? $currentPage + 1 : null,
                'prev_page'        => $currentPage > 1 ? $currentPage - 1 : null,
                'current_page_url' => $paginated['first_page_url'] ? $this->resource->url($currentPage) : null,
                'last_page_url'    => $paginated['last_page_url'],
                'next_page_url'    => $paginated['next_page_url'],
                'prev_page_url'    => $paginated['prev_page_url'],
                'total'            => $paginated['total'],
                'per_page'         => $paginated['per_page'],
                'page'             => $currentPage,
            ],
        ];
    }
}

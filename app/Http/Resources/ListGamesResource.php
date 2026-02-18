<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListGamesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'min_players' => $this->min_players,
            'max_players' => $this->max_players,
            'play_time_minutes' => $this->play_time_minutes,
            'year_published' => $this->year_published,
            'image_url' => $this->image_url,
            'avg_rating' => $this->avg_rating,
            'rating_count' => $this->rating_count,
        ];
    }
}

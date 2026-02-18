<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = fake()->numberBetween(1, 4);

        return [
            'title' => fake()->words(3, true),
            'description' => fake()->text(),
            'rules_summary' => fake()->text(),
            'min_players' => $min,
            'max_players' => fake()->numberBetween($min, 10),
            'play_time_minutes' => fake()->numberBetween(0, 1000),
            'complexity' => fake()->randomFloat(2, 1, 5),
            'year_published' => fake()->year(),
            'image_url' => fake()->imageUrl(),
            'bgg_id' => fake()->unique()->numberBetween(1000, 999999),
            'avg_rating' => fake()->randomFloat(2, 0, 10),
            'rating_count' => fake()->numberBetween(1, 10000),
        ];
    }
}

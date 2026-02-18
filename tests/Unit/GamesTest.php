<?php

use App\Models\Game;

it('model to array', function () {
    $game = Game::factory()->create()->fresh();

    expect(array_keys($game->toArray()))->toBe([
        'id',
        'title',
        'description',
        'rules_summary',
        'min_players',
        'max_players',
        'play_time_minutes',
        'complexity',
        'year_published',
        'image_url',
        'bgg_id',
        'avg_rating',
        'rating_count',
        'created_at',
        'updated_at',
    ]);
});

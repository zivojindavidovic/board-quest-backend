<?php

use App\Models\Game;
use Illuminate\Support\Str;

it('returns paginated games with success flag', function () {
    Game::factory()->count(15)->create()->fresh();

    $response = $this->getJson('/api/v1/games');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data',
            'meta' => [
                'current_page',
                'last_page',
                'next_page',
                'prev_page',
                'total',
                'per_page',
            ]
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('meta.current_page', 1)
        ->assertJsonPath('meta.per_page', 10);
});

it('returns false success flag when games list validation fails with error keys', function () {
    Game::factory()->count(15)->create()->fresh();

    $response = $this->getJson('/api/v1/games?page=0&per_page=1000');

    $response->assertUnprocessable()
        ->assertJsonStructure([
            'success',
            'message',
            'error_code',
            'errors' => [
                'page',
                'per_page',
            ]
        ])
        ->assertJsonPath('success', false);
});

it('returns game with success flag', function () {
    Game::factory()->count(15)->create()->fresh();

    $gameId = Game::query()->first()['id'];

    $response = $this->getJson("/api/v1/games/{$gameId}");

    $response->assertOk();
});

it('returns false success flag when game with uuid does not exist', function () {
    Game::factory()->count(15)->create()->fresh();

    $randomUUID = Str::uuid();

    $response = $this->getJson("/api/v1/games/{$randomUUID}");

    $response->assertNotFound()
        ->assertJsonStructure([
        'success',
        'message',
        'error_code',
        'errors' => [
            'uuid',
        ]
    ])
    ->assertJsonPath('success', false);
});

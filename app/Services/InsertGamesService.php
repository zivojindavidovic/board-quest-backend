<?php

namespace App\Services;

use App\Repositories\GamesRepository;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;

final readonly class InsertGamesService
{
    public function __construct(
        private GetGameFromBoardGameGeekApiService $getGameFromBoardGameGeekApiService,
        private GamesRepository                    $gamesRepository
    )
    {
    }

    /**
     * Process inserts of new games from BGG
     *
     * @throws ConnectionException
     */
    public function execute(array $gameIds): void
    {
        $newGameIds = $this->getNonExistingGameIds($gameIds);
        $preparedData = $this->prepareInsertRows($newGameIds);

        $this->gamesRepository->insert($preparedData);
    }

    /**
     * Returns non-existing game ids
     */
    private function getNonExistingGameIds(array $gameIds): array
    {
        $existingGames = $this->gamesRepository->getManyByBGGId($gameIds);
        return array_filter($gameIds, fn(int $gameId) => !in_array($gameId, $existingGames));
    }

    /**
     * Prepares games for insert
     *
     * @throws ConnectionException
     */
    private function prepareInsertRows(array $gameIds): array
    {
        $games = [];

        $parsedGame = $this->getGameFromBoardGameGeekApiService->execute($gameIds);

        if (!$parsedGame || !isset($parsedGame['item'])) {
            return [];
        }

        $items = isset($parsedGame['item'][0]) ? $parsedGame['item'] : [$parsedGame['item']];

        foreach ($items as $item) {
            $games[] = [
                'id' => Str::uuid(),
                'title' => is_array($item['name']) && isset($item['name'][0])
                    ? $item['name'][0]['@attributes']['value']
                    : $item['name']['@attributes']['value'],
                'description' => $item['description'],
                'rules_summary' => null,
                'min_players' => $item['minplayers']['@attributes']['value'],
                'max_players' => $item['maxplayers']['@attributes']['value'],
                'play_time_minutes' => $item['playingtime']['@attributes']['value'],
                'complexity' => $item['statistics']['ratings']['averageweight']['@attributes']['value'] ?: null,
                'year_published' => $item['yearpublished']['@attributes']['value'],
                'image_url' => $item['image'],
                'bgg_id' => $item['@attributes']['id'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $games;
    }
}

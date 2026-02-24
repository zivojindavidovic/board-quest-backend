<?php

namespace App\Services;

use App\Http\Clients\BoardGameGeekApiClient;
use Illuminate\Http\Client\ConnectionException;

final readonly class GetGamesFromBoardGameGeekApiService
{
    private const string ENDPOINT = 'thing';

    public function __construct(private BoardGameGeekApiClient $client)
    {
    }

    /**
     * Returns array of games from BGG API
     *
     * @throws ConnectionException
     */
    public function execute(array $ids): ?array
    {
        $xml = $this->client->get(self::ENDPOINT, [
            'id'    => implode(',', $ids),
            'stats' => 1,
        ]);

        if (!$xml) {
            return null;
        }

        return json_decode(json_encode(simplexml_load_string($xml)), true);
    }
}

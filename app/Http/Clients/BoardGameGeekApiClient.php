<?php

namespace App\Http\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

final readonly class BoardGameGeekApiClient
{
    /**
     * Accessor for config keys
     *
     * @var string
     */
    private const string CONFIG_KEY = 'app.board_game_geek';

    /**
     * HTTP Status 200
     *
     * @var int
     */
    private const int    HTTP_SUCCESS = 200;

    /**
     * HTTP Status 202
     *
     * @var int
     */
    private const int    HTTP_ACCEPTED = 202;

    /**
     * BGG API Retry limit
     *
     * @var int
     */
    private const int    RETRY_LIMIT = 3;

    /**
     * BGG API Sleep before retry value
     *
     * @var int
     */
    private const int    RETRY_SLEEP = 5;

    /**
     * Returns XML games from BGG API
     *
     * @throws ConnectionException
     */
    public function get(string $endpoint, array $parameters = []): ?string
    {
        $url = Config::get(self::CONFIG_KEY . '.api_url') . $endpoint;
        $retries = 0;

        do {
            $response = Http::withHeaders([
                'Authorization' => Config::get(self::CONFIG_KEY . '.token_type')
                    . ' '
                    . Config::get(self::CONFIG_KEY . '.token'),
            ])->get($url, $parameters);

            if ($response->getStatusCode() === self::HTTP_ACCEPTED) {
                sleep(self::RETRY_SLEEP);
                $retries++;
            }
        } while ($response->getStatusCode() === self::HTTP_ACCEPTED && $retries < self::RETRY_LIMIT);

        return $response->getStatusCode() === self::HTTP_SUCCESS
            ? $response->getBody()->getContents()
            : null;
    }
}

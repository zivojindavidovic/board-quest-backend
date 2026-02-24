<?php

namespace App\Console\Commands;

use App\Jobs\ImportGamesJob;
use App\Services\GetBGGIdsFromCsvService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

final class ImportGamesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate-games:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command imports games from Board Game Geek into database';

    /**
     * The console command name
     *
     * @var string
     */
    protected $name = 'ImportGamesCommand';

    public function __construct(
        private readonly GetBGGIdsFromCsvService $getBGGIdsFromCsvService,
    )
    {
        parent::__construct();
    }

    /**
     * Execute games import
     */
    public function handle(): int
    {
        $latestInsertedRow = Cache::get('bgg_populate_offset', 0);

        try {
            $bggIds = $this->getBGGIdsFromCsvService->execute($latestInsertedRow, 500);

            if (empty($bggIds)) {
                return self::FAILURE;
            }

            $bggIdChunks = array_chunk($bggIds, 20);
            $jobs = array_map(fn($chunk) => new ImportGamesJob($chunk), $bggIdChunks);

            Bus::batch($jobs)
                ->catch(fn($batch, Throwable $e) => Log::error('BGG batch failed', [
                    'batch_id' => $batch->id,
                    'error'    => $e->getMessage(),
                ]))
                ->dispatch();

            Cache::put('bgg_populate_offset', $latestInsertedRow + count($bggIds));

            return self::SUCCESS;
        } catch (Throwable $e) {
            Log::error('ImportGamesCommand failed', ['error' => $e->getMessage()]);
            return self::FAILURE;
        }
    }
}

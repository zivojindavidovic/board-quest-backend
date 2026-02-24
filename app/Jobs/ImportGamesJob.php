<?php

namespace App\Jobs;

use App\Services\InsertGamesService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportGamesJob implements ShouldQueue
{
    use Queueable, Batchable;

    public function __construct(private readonly array $chunk)
    {
    }

    /**
     * Handles importing games batch
     *
     * @throws ConnectionException
     */
    public function handle(InsertGamesService $populateGamesService): void
    {
        $populateGamesService->execute($this->chunk);
    }

    /**
     * Handles exception
     */
    public function failed(Throwable $exception): void
    {
        Log::error('ImportGamesJob failed', [
            'chunk' => $this->chunk,
            'error' => $exception->getMessage(),
        ]);
    }
}

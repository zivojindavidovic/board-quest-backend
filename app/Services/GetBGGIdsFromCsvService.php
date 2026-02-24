<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\UnableToProcessCsv;
use League\Csv\UnavailableStream;
use ReflectionException;

final readonly class GetBGGIdsFromCsvService
{
    /**
     * Returns required number of game ids from csv starting from offset
     *
     * @throws Exception|ReflectionException|UnableToProcessCsv|UnavailableStream
     */
    public function execute(int $startFrom, int $numberOfIdsToRead): array
    {
        return $this->getIdsFromCSV($startFrom, $numberOfIdsToRead);
    }

    /**
     * Returns game ids from CSV
     *
     * @throws Exception|ReflectionException|UnableToProcessCsv|UnavailableStream
     */
    private function getIdsFromCSV($startFrom, $numberOfIdsToRead): array
    {
        $csv = Reader::createFromPath(Storage::disk('public')->path('boardgames_database_2026_02_17.csv'));
        $csv->setHeaderOffset(0);

        $statement = Statement::create()
            ->offset($startFrom)
            ->limit($numberOfIdsToRead);

        $records = $statement->process($csv);

        return iterator_to_array($records->fetchColumn('id'), false);
    }
}

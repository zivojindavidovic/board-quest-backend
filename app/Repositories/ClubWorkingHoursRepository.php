<?php

namespace App\Repositories;

use App\Models\ClubWorkingHour;

final readonly class ClubWorkingHoursRepository
{
    /*
     * Inserts multiple club working hours
     */
    public function insert(array $workingHours): bool
    {
        return ClubWorkingHour::query()
            ->insert($workingHours);
    }
}

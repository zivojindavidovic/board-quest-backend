<?php

namespace App\Services;

use App\Repositories\ClubsGamesRepository;
use App\Repositories\ClubsRepository;
use App\Repositories\ClubWorkingHoursRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

final readonly class RegisterClubService
{
    public function __construct(
        private ClubsRepository $clubsRepository,
        private ClubWorkingHoursRepository $clubWorkingHoursRepository,
        private ClubsGamesRepository $clubsGamesRepository
    ) {}

    /**
     * Executes creating club
     *
     * @throws Throwable
     */
    public function execute(array $data)
    {
        $club = $this->formatClubData($data);

        return DB::transaction(function () use ($data, $club) {
            $club = $this->clubsRepository->create($club);

            $clubWorkingHours = $this->formatClubWorkingHours($data, $club['id']);
            $this->clubWorkingHoursRepository->insert($clubWorkingHours);

            $this->clubsGamesRepository->sync($club, $data['available_games']);

            return $club->load([
                'games',
                'workingHours',
            ]);
        });
    }

    /*
     * Formats club data for insert
     */
    private function formatClubData(array $data): array
    {
        return [
            'id' => Str::uuid(),
            'user_id' => Auth::id() ?? 0,
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'address' => $data['address'],
            'city' => $data['city'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'website' => $data['website'] ?? null,
            'image_url' => $data['image_url'] ?? null,
        ];
    }

    /*
     * Formats club working hours.
     *
     * - One day can have multiple working ranges
     * - If ranges overlap it inserts one range
     */
    private function formatClubWorkingHours(array $data, string $clubId): array
    {
        $this->sortWorkingHours($data);

        $workingHours = [];

        foreach ($data['working_days'] as $day) {
            foreach ($data['working_hours'][$day] as $timeRange) {
                if ($this->isCurrentOpenTimeBetweenPreviousRange($workingHours, $day, $timeRange)) {
                    $this->updatePreviousWorkingHours($workingHours, $timeRange);

                    continue;
                }

                $workingHours[] = [
                    'id' => Str::uuid(),
                    'club_id' => $clubId,
                    'day_of_week' => $day,
                    'open_time' => $timeRange['open_time'],
                    'close_time' => $timeRange['close_time'],
                ];
            }
        }

        return $workingHours;
    }

    /*
     * Sorts working hours ascending and prepares it for comparison
     */
    private function sortWorkingHours(array &$data): void
    {
        foreach ($data['working_hours'] as &$dayRanges) {
            usort($dayRanges, fn ($a, $b) => strcmp($a['open_time'], $b['open_time']));
        }
    }

    /*
     * Check is previous inserted working hour range should be extended with next
     */
    private function isCurrentOpenTimeBetweenPreviousRange(array $workingHours, int $day, array $timeRange): bool
    {
        $totalWorkingHours = count($workingHours);

        return $totalWorkingHours && $day === $workingHours[$totalWorkingHours - 1]['day_of_week'] && $timeRange['open_time'] < $workingHours[$totalWorkingHours - 1]['close_time'];
    }

    /*
     * Updates previous formatted working hour
     */
    private function updatePreviousWorkingHours(array &$workingHours, array $timeRange): void
    {
        $totalWorkingHours = count($workingHours);
        $workingHours[$totalWorkingHours - 1]['close_time'] = max(
            $workingHours[$totalWorkingHours - 1]['close_time'],
            $timeRange['close_time']
        );
    }
}

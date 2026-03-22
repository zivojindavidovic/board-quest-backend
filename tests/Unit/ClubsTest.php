<?php

use App\Services\RegisterClubService;

it('keeps non-overlapping ranges as separate entries', function () {
    $method = new ReflectionMethod(RegisterClubService::class, 'formatClubWorkingHours');
    $method->setAccessible(true);

    $data = [
        'working_days' => [1],
        'working_hours' => [
            1 => [
                ['open_time' => '08:00', 'close_time' => '12:00'],
                ['open_time' => '14:00', 'close_time' => '18:00'],
            ],
        ],
    ];

    $reflection = new ReflectionClass(RegisterClubService::class);
    $service = $reflection->newInstanceWithoutConstructor();

    $result = $method->invoke($service, $data, 'test-club-id');

    expect($result)->toHaveCount(2)
        ->and($result[0])->toMatchArray([
            'club_id' => 'test-club-id',
            'day_of_week' => 1,
            'open_time' => '08:00',
            'close_time' => '12:00',
        ])
        ->and($result[1])->toMatchArray([
            'club_id' => 'test-club-id',
            'day_of_week' => 1,
            'open_time' => '14:00',
            'close_time' => '18:00',
        ]);
});

it('merges partially overlapping ranges into one', function () {
    $method = new ReflectionMethod(RegisterClubService::class, 'formatClubWorkingHours');
    $method->setAccessible(true);

    $data = [
        'working_days' => [1],
        'working_hours' => [
            1 => [
                ['open_time' => '08:00', 'close_time' => '14:00'],
                ['open_time' => '12:00', 'close_time' => '18:00'],
            ],
        ],
    ];

    $reflection = new ReflectionClass(RegisterClubService::class);
    $service = $reflection->newInstanceWithoutConstructor();

    $result = $method->invoke($service, $data, 'test-club-id');

    expect($result)->toHaveCount(1)
        ->and($result[0])->toMatchArray([
            'club_id' => 'test-club-id',
            'day_of_week' => 1,
            'open_time' => '08:00',
            'close_time' => '18:00',
        ]);
});

it('absorbs a fully contained range into the larger one', function () {
    $method = new ReflectionMethod(RegisterClubService::class, 'formatClubWorkingHours');
    $method->setAccessible(true);

    $data = [
        'working_days' => [1],
        'working_hours' => [
            1 => [
                ['open_time' => '08:00', 'close_time' => '18:00'],
                ['open_time' => '10:00', 'close_time' => '14:00'],
            ],
        ],
    ];

    $reflection = new ReflectionClass(RegisterClubService::class);
    $service = $reflection->newInstanceWithoutConstructor();

    $result = $method->invoke($service, $data, 'test-club-id');

    expect($result)->toHaveCount(1)
        ->and($result[0])->toMatchArray([
            'club_id' => 'test-club-id',
            'day_of_week' => 1,
            'open_time' => '08:00',
            'close_time' => '18:00',
        ]);
});

it('merges adjacent ranges where close time equals next open time', function () {
    $method = new ReflectionMethod(RegisterClubService::class, 'formatClubWorkingHours');
    $method->setAccessible(true);

    $data = [
        'working_days' => [1],
        'working_hours' => [
            1 => [
                ['open_time' => '08:00', 'close_time' => '18:00'],
                ['open_time' => '18:00', 'close_time' => '20:00'],
            ],
        ],
    ];

    $reflection = new ReflectionClass(RegisterClubService::class);
    $service = $reflection->newInstanceWithoutConstructor();

    $result = $method->invoke($service, $data, 'test-club-id');

    expect($result)->toHaveCount(1)
        ->and($result[0])->toMatchArray([
            'club_id' => 'test-club-id',
            'day_of_week' => 1,
            'open_time' => '08:00',
            'close_time' => '20:00',
        ]);
});

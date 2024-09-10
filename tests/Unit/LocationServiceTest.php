<?php

use App\Services\LocationService;

//Check if distance is calculated correctly
test('check if distance is calculated correctly', function () {
    $locationService = new LocationService();
    $location1 = ['latitude' => 40.7128, 'longitude' => -74.0060];
    $location2 = ['latitude' => 34.0522, 'longitude' => -118.2437];

    $distance = round($locationService->calculateDistance($location1,$location2));

    expect($distance)->toBe(3936.0);
});

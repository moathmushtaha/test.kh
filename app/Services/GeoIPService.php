<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use MaxMind\Db\Reader\InvalidDatabaseException;

class GeoIPService
{
    protected $reader;

    /**
     * @throws InvalidDatabaseException
     */
    public function __construct()
    {
        $this->reader = new Reader(database_path('maxmind/GeoLite2-City.mmdb'));
    }

    public function getLocationFromIp(string $ip)
    {
        try {
            $record = $this->reader->city($ip);

            return [
                'city' => $record->city->name,
                'country' => $record->country->name,
                'latitude' => $record->location->latitude,
                'longitude' => $record->location->longitude,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}

<?php

namespace App\Services;

use App\Exceptions\InvalidIPException;
use Stevebauman\Location\Facades\Location;

class LocationService
{
    /**
     * @throws InvalidIPException
     */
    public function getLocation($latitude, $longitude, $ip)
    {
        if (is_null($latitude) && is_null($longitude)) {
            return $this->getLocationFromIp($ip);
        }

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }
    public function getLocationFromIp($ip)
    {
        $location = Location::get($ip);
        if (!$location){
            throw new InvalidIPException($ip);
        }

        return [
            'latitude' => $location->latitude,
            'longitude' => $location->longitude,
        ];
    }

    public function calculateDistance($location1, $location2)
    {
        $lat1 = $location1['latitude'];
        $lon1 = $location1['longitude'];
        $lat2 = $location2['latitude'];
        $lon2 = $location2['longitude'];

        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return null;
        }

        $earthRadius = 6371; // Radius of the earth in km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance; // Distance in km
    }
}

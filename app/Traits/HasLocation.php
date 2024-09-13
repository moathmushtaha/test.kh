<?php

namespace App\Traits;

trait HasLocation
{
    public function getLocation()
    {
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }

    public function updateLocation($location)
    {
        $new_latitude = $location['latitude'];
        $new_longitude = $location['longitude'];

        if ($this->latitude == $new_latitude && $this->longitude == $new_longitude) {
            return;
        }

        $this->update([
            'latitude' => $location['latitude'],
            'longitude' => $location['longitude'],
        ]);
    }

    public function getDistanceTo($model)
    {
        $current_user_location = $this->getLocation();
        $with_user_location = $model->getLocation();
        return $this->calculateDistance($current_user_location, $with_user_location);
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

        return round($earthRadius * $c); // Distance in km
    }

}

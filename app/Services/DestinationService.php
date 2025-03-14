<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Destination;
class DestinationService implements \App\Services\DestinationServiceInteface
{
    /**
     * @param array $data
     * @return \Illuminate\Support\Collection
     */
    public function getDestinationsWithinRadius(array $data): \Illuminate\Support\Collection
    {
        list($lat, $lon, $radius) = [$data['lat'], $data['lon'], $data['radius']];

        $destinations = Destination::selectRaw("
                DISTINCT id, name, lat, lon,
                ROUND((6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(lat))
                * COS(RADIANS(lon) - RADIANS(?)) + SIN(RADIANS(?))
                * SIN(RADIANS(lat)))), 2) AS distance
            ", [$lat, $lon, $lat])
            ->whereRaw("
                ROUND((6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(lat))
                * COS(RADIANS(lon) - RADIANS(?)) + SIN(RADIANS(?))
                * SIN(RADIANS(lat)))), 2) < ?
            ", [$lat, $lon, $lat, $radius])
            ->orderBy("distance", "asc")
            ->get();

        return $destinations->filter(function ($item) use ($radius) {
            return $item->distance <= $radius;
        });
    }
}

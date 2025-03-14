<?php

namespace App\Services;

interface DestinationServiceInteface
{
    public function getDestinationsWithinRadius(array $data): \Illuminate\Support\Collection;
}

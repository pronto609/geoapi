<?php

namespace Tests\Unit\Services;

use App\Models\Destination;
use App\Services\DestinationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DestinationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->service = new DestinationService();
    }

    public function test_get_destinations_within_radius_returns_correct_data()
    {
        Destination::factory()->create(['lat' => 41.9, 'lon' => 12.5]); // Внутри радиуса
        Destination::factory()->create(['lat' => 42.5, 'lon' => 13.5]); // За пределами радиуса

        $result = $this->service->getDestinationsWithinRadius(41.9028, 12.4964, 50);

        $this->assertNotEmpty($result);

        foreach ($result as $destination) {
            $this->assertLessThanOrEqual(50, $destination->distance);
        }
    }
}

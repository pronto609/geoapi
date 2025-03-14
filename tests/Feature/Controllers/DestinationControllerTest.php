<?php

namespace Tests\Feature\Controllers;

use App\Services\DestinationServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class DestinationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_destinations_returns_correct_response()
    {
        // Создаем мок сервиса
        $mockService = Mockery::mock(DestinationServiceInterface::class);
        $this->app->instance(DestinationServiceInterface::class, $mockService);

        // Подготавливаем тестовые данные
        $mockData = collect([
            (object) ['id' => 1, 'name' => 'Rome', 'lat' => 41.9, 'lon' => 12.5, 'distance' => 10.0],
            (object) ['id' => 2, 'name' => 'Milan', 'lat' => 45.4, 'lon' => 9.2, 'distance' => 40.0],
        ]);

        // Ожидаем, что сервис вернет мокированные данные
        $mockService->shouldReceive('getDestinationsWithinRadius')
            ->with(41.9028, 12.4964, 50)
            ->once()
            ->andReturn($mockData);

        // Делаем запрос к API
        $response = $this->getJson('/api/destinations?lat=41.9028&lon=12.4964&radius=50');

        // Проверяем код ответа
        $response->assertStatus(200);

        // Проверяем структуру ответа
        $response->assertJsonCount(2)
            ->assertJson([
                ['id' => 1, 'name' => 'Rome', 'distance' => 10.0],
                ['id' => 2, 'name' => 'Milan', 'distance' => 40.0],
            ]);
    }
}

<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestinationRequest;
use App\Services\DestinationServiceInteface;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class DestinationController extends Controller
{
    /**
     * @param DestinationServiceInteface $destinationServiceInteface
     */
    public function __construct(
        private readonly DestinationServiceInteface $destinationServiceInteface
    ) {
    }

    /**
     * @param DestinationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function findNearby(DestinationRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validated();

            $filtered = $this->destinationServiceInteface->getDestinationsWithinRadius($validated);

            return response()->json($filtered);
        } catch (NotFound $exception) {
            return response()->json(['No result']);
        }
    }
}

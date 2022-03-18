<?php

namespace App\Http\Controllers;

use App\Http\Requests\FixtureUpdateRequest;
use App\Services\FixtureService;
use Illuminate\Http\JsonResponse;

class FixtureController extends Controller
{
    private FixtureService $fixtureService;

    /**
     * FixtureController Constructor
     * @param  FixtureService  $fixtureService
     */
    public function __construct(FixtureService $fixtureService)
    {
        $this->fixtureService = $fixtureService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(['fixtures' => $this->fixtureService->getAllFixtures()]);
    }

    /**
     * @return JsonResponse
     */
    public function generate(): JsonResponse
    {
        return response()->json(['fixtures' => $this->fixtureService->generate()]);
    }

    /**
     * @return JsonResponse
     */
    public function playNextWeek(): JsonResponse
    {
        $this->fixtureService->playNextWeek();

        return response()->json([], 201);
    }

    /**
     * @return JsonResponse
     */
    public function playAll(): JsonResponse
    {
        $this->fixtureService->playAll();

        return response()->json([], 201);
    }

    /**
     * @param  FixtureUpdateRequest  $fixtureUpdateRequest
     * @param  int  $fixtureId
     * @return JsonResponse
     */
    public function update(FixtureUpdateRequest $fixtureUpdateRequest, int $fixtureId): JsonResponse
    {
        if ($this->fixtureService->update($fixtureId, $fixtureUpdateRequest->validated())) {
            return response()->json();
        }

        return response()->json(['message' => 'error'], 400);
    }

    /**
     * @return JsonResponse
     */
    public function resetAllFixtures(): JsonResponse
    {
        if ($this->fixtureService->resetAllFixtures()) {
            return response()->json(['message' => 'successful']);
        }

        return response()->json(['message' => 'error'], 400);
    }
}

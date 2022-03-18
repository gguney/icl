<?php

namespace App\Http\Controllers;

use App\Services\TeamService;
use Illuminate\Http\JsonResponse;

class TeamController extends Controller
{
    /**
     * @param  TeamService  $teamService
     * @return JsonResponse
     */
    public function index(TeamService $teamService): JsonResponse
    {
        return response()->json(['teams' => $teamService->getAllTeams()]);
    }
}

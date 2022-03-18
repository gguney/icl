<?php

namespace App\Services;

use App\Repositories\Interfaces\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TeamService
{
    private TeamRepositoryInterface $teamRepository;

    /**
     * TeamService Constructor
     *
     * @param  TeamRepositoryInterface  $teamRepository
     */
    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @return Collection
     */
    public function getAllTeams(): Collection
    {
        return $this->teamRepository
            ->getAllTeams()
            ->each(
                function ($team) {
                    $team->append([
                        'points',
                        'played',
                        'win',
                        'draw',
                        'loose',
                        'goalDifference',
                        'scoredGoals',
                        'concededGoals',
                    ]);
                }
            )
            ->sortByDesc('goalDifference')
            ->sortByDesc('points')
            ->values();
    }
}

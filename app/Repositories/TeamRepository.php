<?php

namespace App\Repositories;

use App\Models\Team;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    protected Team $team;

    /**
     * @param  Team  $team
     */
    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @return Collection
     */
    public function getAllTeams(): Collection
    {
        return $this->getAllTeamsQuery()->get();
    }

    /**
     * @return Collection
     */
    public function getAllTeamsInRandomOrder(): Collection
    {
        return $this->getAllTeamsQuery()->inRandomOrder()->get();
    }

    /**
     * @return Builder
     */
    private function getAllTeamsQuery(): Builder
    {
        return $this->team->with(['stadium', 'homeTeamFixtures', 'awayTeamFixtures']);
    }
}

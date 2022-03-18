<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAllTeams(): Collection;

    /**
     * @return Collection
     */
    public function getAllTeamsInRandomOrder(): Collection;
}

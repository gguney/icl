<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

interface FixtureRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAllFixtures(): Collection;

    /**
     * @param  array  $fixturesData
     * @return BaseCollection
     */
    public function createFixtures(array $fixturesData): BaseCollection;

    /**
     * @return Collection
     */
    public function getNextWeekFixtures(): Collection;

    /**
     * @return int
     */
    public function getCurrentWeekNumber(): int;

    /**
     * @return int
     */
    public function getFinalWeekNumber(): int;

    /**
     * @param  int  $fixtureId
     * @param  array  $fixtureData
     * @return bool
     */
    public function update(int $fixtureId, array $fixtureData): bool;

    /**
     * @return bool
     */
    public function resetAllFixtures(): bool;

    /**
     * @return bool
     */
    public function deleteAllFixtures(): bool;
}

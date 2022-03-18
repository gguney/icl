<?php

namespace App\Repositories;

use App\Models\Fixture;
use App\Repositories\Interfaces\FixtureRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as BaseCollection;

class FixtureRepository implements FixtureRepositoryInterface
{
    protected Fixture $fixture;

    /**
     * @param  Fixture  $fixture
     */
    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture;
    }

    /**
     * @return Collection
     */
    public function getAllFixtures(): Collection
    {
        return $this->getAllFixturesQuery()->get();
    }

    /**
     * @param  array  $fixturesData
     * @return BaseCollection
     */
    public function createFixtures(array $fixturesData): BaseCollection
    {
        $insertedFixtures = collect([]);

        foreach ($fixturesData as $fixtureData) {
            $insertedFixtures->push($this->fixture->create($fixtureData));
        }

        return $insertedFixtures;
    }

    /**
     * @return Collection
     */
    public function getNextWeekFixtures(): Collection
    {
        return $this->getAllFixturesQuery()->where('week', $this->getCurrentWeekNumber() + 1)->get();
    }

    /**
     * @return int
     */
    public function getCurrentWeekNumber(): int
    {
        return $this->fixture->played()->max('week') ?? 0;
    }

    /**
     * @return int
     */
    public function getFinalWeekNumber(): int
    {
        return $this->fixture->max('week');
    }

    /**
     * @param  int  $fixtureId
     * @param  array  $fixtureData
     * @return bool
     */
    public function update(int $fixtureId, array $fixtureData): bool
    {
        return $this->fixture->findOrFail($fixtureId)->update($fixtureData);
    }

    /**
     * @return bool
     */
    public function resetAllFixtures(): bool
    {
        return $this->fixture->query()->update(['home_team_score' => null, 'away_team_score' => null]);
    }

    /**
     * @return bool
     */
    public function deleteAllFixtures(): bool
    {
        return $this->fixture->query()->delete();
    }

    /**
     * @return Builder
     */
    private function getAllFixturesQuery(): Builder
    {
        return $this->fixture->with(['homeTeam', 'awayTeam', 'stadium']);
    }
}

<?php

namespace App\Services;

use App\Models\Fixture;
use App\Repositories\Interfaces\FixtureRepositoryInterface;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as BaseCollection;

class FixtureService
{
    private FixtureRepositoryInterface $fixtureRepository;
    private TeamRepositoryInterface $teamRepository;

    /**
     * FixtureService Constructor
     * @param  FixtureRepositoryInterface  $fixtureRepository
     * @param  TeamRepositoryInterface  $teamRepository
     */
    public function __construct(
        FixtureRepositoryInterface $fixtureRepository,
        TeamRepositoryInterface $teamRepository
    ) {
        $this->fixtureRepository = $fixtureRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @return Collection
     */
    public function getAllFixtures(): Collection
    {
        return $this->fixtureRepository->getAllFixtures();
    }

    /**
     * @return BaseCollection
     */
    public function generate(): BaseCollection
    {
        $this->fixtureRepository->deleteAllFixtures();

        return $this->fixtureRepository
            ->createFixtures($this->prepareFixtureForTeams($this->teamRepository->getAllTeamsInRandomOrder()));
    }

    /**
     * @return void
     */
    public function playAll()
    {
        while ($this->fixtureRepository->getFinalWeekNumber() > $this->fixtureRepository->getCurrentWeekNumber()) {
            $this->playNextWeek();
        }
    }

    /**
     * @return void
     */
    public function playNextWeek()
    {
        if ($this->fixtureRepository->getFinalWeekNumber() == $this->fixtureRepository->getCurrentWeekNumber()) {
            return;
        }

        foreach ($this->fixtureRepository->getNextWeekFixtures() as $nextWeekFixture) {
            $nextWeekFixture->update($this->calculateMatchScores($nextWeekFixture));
        }
    }

    /**
     * @param  Fixture  $fixture
     * @return array
     */
    private function calculateMatchScores(Fixture $fixture): array
    {
        $powerBoostForHomeTeam = 10;
        $homeTeamGoalChances = ceil(
            rand($powerBoostForHomeTeam, $powerBoostForHomeTeam + $fixture->homeTeam->power) / 10
        );
        $awayTeamGoalChances = round(rand(0, $fixture->awayTeam->power) / 10);

        return [
            'home_team_score' => $this->rollDices($homeTeamGoalChances),
            'away_team_score' => $this->rollDices($awayTeamGoalChances),
        ];
    }

    /**
     * @param  int  $times
     * @return int
     */
    private function rollDices(int $times): int
    {
        $successCount = 0;

        for ($i = 0; $i < $times; $i++) {
            $successCount += (int)$this->rollADice();
        }

        return $successCount;
    }

    /**
     * @return bool
     */
    public function rollADice(): bool
    {
        return rand(1, 6) > rand(1, 6);
    }

    /**
     * @return bool
     */
    public function resetAllFixtures(): bool
    {
        return $this->fixtureRepository->resetAllFixtures();
    }

    /**
     * @param  int  $fixtureId
     * @param  array  $fixtureData
     * @return bool
     */
    public function update(int $fixtureId, array $fixtureData): bool
    {
        return $this->fixtureRepository->update($fixtureId, $fixtureData);
    }

    /**
     * Circle Method used for calculating team crosses.
     * https://en.wikipedia.org/wiki/Round-robin_tournament
     *
     * Match Count Per Week = Team Count / 2
     * Total Week Count = (Team Count - 1) * 2
     *
     * Example:
     * Team Count: 4, Match Count Per Week: 2, Total Week Count: 6
     *
     * @param  Collection  $teams
     * @return array
     */
    private function prepareFixtureForTeams(Collection $teams): array
    {
        $initialTeamIds = $teams->pluck('id');
        $lastWeekTeamIds = collect([]);
        $teamsCount = $teams->count();
        $tournamentWeekCount = $teamsCount - 1;
        $teamsByIds = $teams->keyBy('id');
        $week = 1;
        $fixturesData = [];

        while ($initialTeamIds->diffAssoc($lastWeekTeamIds)->count() > 0) {
            if ($lastWeekTeamIds->isEmpty()) {
                $lastWeekTeamIds = clone $initialTeamIds;
            }

            for ($i = 0; $i < $teamsCount / 2; $i++) {
                $firstTeamId = $lastWeekTeamIds[$i];
                $secondTeamId = $lastWeekTeamIds[$teamsCount / 2 + $i];

                $fixturesData[] = [
                    'home_team_id' => $firstTeamId,
                    'away_team_id' => $secondTeamId,
                    'stadium_id' => $teamsByIds[$firstTeamId]->stadium->id,
                    'week' => $week,
                ];

                $fixturesData[] = [
                    'home_team_id' => $secondTeamId,
                    'away_team_id' => $firstTeamId,
                    'stadium_id' => $teamsByIds[$secondTeamId]->stadium->id,
                    'week' => $week + $tournamentWeekCount,
                ];
            }

            $lastWeekTeamIds->splice(1, 0, $lastWeekTeamIds->pop());
            $week++;
        }

        return Arr::sort($fixturesData, fn($value) => $value['week']);
    }
}

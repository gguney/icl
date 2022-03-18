<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends BaseModel
{
    private const WIN_POINT = 3;
    private const DRAW_POINT = 1;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($team) {
            Stadium::factory()->create(['name' => $team->name . ' Stadium', 'owner_team_id' => $team->id]);
        });
    }

    /**
     * @return int
     */
    public function getPointsAttribute(): int
    {
        return self::WIN_POINT * $this->win + self::DRAW_POINT * $this->draw;
    }

    /**
     * @return int
     */
    public function getPlayedAttribute(): int
    {
        return $this->homeTeamFixtures->whereNotNull('home_team_score')->count() +
            $this->awayTeamFixtures->whereNotNull('home_team_score')->count();
    }

    /**
     * @return int
     */
    public function getWinAttribute(): int
    {
        return $this->homeTeamFixtures
            ->whereNotNull('home_team_score')
            ->filter(
                function ($fixture) {
                    return $fixture->home_team_score > $fixture->away_team_score;
                }
            )
            ->count() +
            $this->awayTeamFixtures
                ->whereNotNull('away_team_score')
                ->filter(
                    function ($fixture) {
                        return $fixture->away_team_score > $fixture->home_team_score;
                    }
                )
                ->count();
    }

    /**
     * @return int
     */
    public function getLooseAttribute(): int
    {
        return $this->homeTeamFixtures
            ->whereNotNull('home_team_score')
            ->filter(
                function ($fixture) {
                    return $fixture->home_team_score < $fixture->away_team_score;
                }
            )
            ->count() +
            $this->awayTeamFixtures
                ->whereNotNull('away_team_score')
                ->filter(
                    function ($fixture) {
                        return $fixture->away_team_score < $fixture->home_team_score;
                    }
                )
                ->count();
    }

    /**
     * @return int
     */
    public function getDrawAttribute(): int
    {
        return $this->homeTeamFixtures
                ->whereNotNull('home_team_score')
                ->filter(
                    function ($fixture) {
                        return $fixture->home_team_score == $fixture->away_team_score;
                    }
                )
                ->count() +
            $this->awayTeamFixtures
                ->whereNotNull('away_team_score')
                ->filter(
                    function ($fixture) {
                        return $fixture->away_team_score == $fixture->home_team_score;
                    }
                )
                ->count();
    }

    /**
     * @return int
     */
    public function getGoalDifferenceAttribute(): int
    {
        return $this->scored_goals - $this->conceded_goals;
    }

    /**
     * @return int
     */
    public function getScoredGoalsAttribute(): int
    {
        return $this->homeTeamFixtures->sum('home_team_score') + $this->awayTeamFixtures->sum('away_team_score');
    }

    /**
     * @return int
     */
    public function getConcededGoalsAttribute(): int
    {
        return $this->homeTeamFixtures->sum('away_team_score') + $this->awayTeamFixtures->sum('home_team_score');
    }

    /**
     * @return HasOne
     */
    public function stadium(): HasOne
    {
        return $this->hasOne(Stadium::class, 'owner_team_id');
    }

    /**
     * @return HasMany
     */
    public function homeTeamFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'home_team_id');
    }

    /**
     * @return HasMany
     */
    public function awayTeamFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'away_team_id');
    }

    /**
     * @return bool|null
     */
    public function delete(): ?bool
    {
        $this->stadium()->delete();

        return parent::delete();
    }
}

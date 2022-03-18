<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fixture>
 */
class FixtureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $homeTeam = Team::factory()->create();

        return [
            'home_team_id' => $homeTeam->id,
            'away_team_id' => Team::factory()->create(),
            'stadium_id' => $homeTeam->stadium->id,
            'week' => rand(1, 1000),
            'home_team_score' => rand(0, 10),
            'away_team_score' => rand(0, 10),
        ];
    }
}

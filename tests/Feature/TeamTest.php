<?php

namespace Tests\Feature;

use App\Models\Fixture;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_all_teams()
    {
        $uri = '/api/teams';

        $newTeams = Team::factory()->count(rand(1, 10))->create();

        $response = $this->json('GET', $uri);

        $response->assertOk();

        foreach ($newTeams as $newTeam) {
            $response->assertJsonFragment($newTeam->toArray());
        }
    }

    /** @test */
    public function it_should_return_all_teams_with_stats()
    {
        $uri = '/api/teams';

        $teamA = Team::factory()->create(['name' => 'A']);
        $teamB = Team::factory()->create(['name' => 'B']);
        $homeMatchScore = rand(1, 10);
        $awayMatchScore = rand(1, 10);

        Fixture::factory()
            ->create([
                'home_team_id' => $teamA->id,
                'away_team_id' => $teamB->id,
                'home_team_score' => $homeMatchScore,
                'away_team_score' => 0,
            ]);
        Fixture::factory()
            ->create([
                'home_team_id' => $teamB->id,
                'away_team_id' => $teamA->id,
                'home_team_score' => $awayMatchScore,
                'away_team_score' => 0,
            ]);

        $response = $this->json('GET', $uri);

        $response->assertOk();

        $response->assertJsonFragment([
            'name' => 'A',
            'points' => 3,
            'played' => 2,
            'win' => 1,
            'draw' => 0,
            'loose' => 1,
            'goalDifference' => $homeMatchScore - $awayMatchScore
        ]);
        $response->assertJsonFragment([
            'name' => 'B',
            'points' => 3,
            'played' => 2,
            'win' => 1,
            'draw' => 0,
            'loose' => 1,
            'goalDifference' => $awayMatchScore - $homeMatchScore
        ]);
    }
}

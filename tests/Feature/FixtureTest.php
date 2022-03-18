<?php

namespace Tests\Feature;

use App\Models\Fixture;
use App\Models\Team;
use App\Repositories\Interfaces\FixtureRepositoryInterface;
use App\Repositories\Interfaces\TeamRepositoryInterface;
use App\Services\FixtureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Mockery as m;
use Symfony\Component\HttpFoundation\Response;

class FixtureTest extends BaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_get_all_fixtures()
    {
        $fixtures = Fixture::factory()->count(rand(1, 10))->create();

        $response = $this->json('GET', '/api/fixtures/');

        $response->assertOk();

        foreach ($fixtures as $fixture) {
            $response->assertJsonFragment($fixture->toArray());
        }
    }

    /** @test */
    public function it_should_generate_fixtures()
    {
        $teamA = Team::factory()->create(['name' => 'A']);
        $teamB = Team::factory()->create(['name' => 'B']);

        $response = $this->json('POST', '/api/fixtures/generate');

        $response->assertOk();

        $this->assertDatabaseHas(
            'fixtures',
            ['home_team_id' => $teamA->id, 'away_team_id' => $teamB->id, 'stadium_id' => $teamA->stadium->id]
        );
        $this->assertDatabaseHas(
            'fixtures',
            ['home_team_id' => $teamB->id, 'away_team_id' => $teamA->id, 'stadium_id' => $teamB->stadium->id]
        );
    }

    /** @test */
    public function it_should_generate_fixtures_with_proper_weeks_and_match_counts()
    {
        $teamCount = rand(1, 20) * 2;
        $matchCountPerWeek = $teamCount / 2;
        $totalWeekCount = ($teamCount - 1) * 2;
        $totalMatchCount = $matchCountPerWeek * $totalWeekCount;

        Team::factory()->count($teamCount)->create();

        $response = $this->json('POST', '/api/fixtures/generate');

        $response->assertOk()
            ->assertSee('fixtures')
            ->assertJsonCount($totalMatchCount, 'fixtures');

        $this->assertEquals(Fixture::max('week'), $totalWeekCount);
    }

    /** @test */
    public function it_should_reset_all_fixtures()
    {
        $fixtures = Fixture::factory()->count(rand(1, 10))->create();

        $response = $this->json('POST', 'api/fixtures/reset-all');

        $response->assertOk();

        foreach ($fixtures as $fixture) {
            $fixtureData = $fixture->toArray();
            $fixtureData['home_team_score'] = null;
            $fixtureData['away_team_score'] = null;
            unset($fixtureData['is_played']);
            unset($fixtureData['created_at']);
            unset($fixtureData['updated_at']);

            $this->assertDatabaseHas('fixtures', $fixtureData);
        }
    }

    /** @test */
    public function it_should_play_next_week()
    {
        $teamA = Team::factory()->create(['name' => 'A', 'power' => 0]);
        $teamB = Team::factory()->create(['name' => 'B', 'power' => 100]);

        $firstWeekPlayedFixtureData = Fixture::factory()
            ->create(['home_team_id' => $teamA->id, 'away_team_id' => $teamB->id, 'week' => 1])
            ->toArray();
        unset($firstWeekPlayedFixtureData['created_at']);
        unset($firstWeekPlayedFixtureData['updated_at']);
        unset($firstWeekPlayedFixtureData['is_played']);

        $secondWeekFixture = Fixture::factory()
            ->create([
                'home_team_id' => $teamB->id,
                'away_team_id' => $teamA->id,
                'week' => 2,
                'home_team_score' => null,
                'away_team_score' => null,
            ]);

        m::mock(
            FixtureService::class,
            [resolve(FixtureRepositoryInterface::class), resolve(TeamRepositoryInterface::class)]
        )
            ->makePartial()
            ->shouldReceive('rollADice')
            ->andReturn(1);

        $response = $this->json('POST', 'api/fixtures/play-next-week');

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('fixtures', $firstWeekPlayedFixtureData)
            ->assertDatabaseHas('fixtures', ['id' => $secondWeekFixture->id, 'away_team_score' => 0]);
    }

    /** @test */
    public function it_should_play_all()
    {
        $teamA = Team::factory()->create(['name' => 'A', 'power' => 100]);
        $teamB = Team::factory()->create(['name' => 'B', 'power' => 0]);

        $firstWeekFixture = Fixture::factory()
            ->create([
                'home_team_id' => $teamA->id,
                'away_team_id' => $teamB->id,
                'week' => 1,
                'home_team_score' => null,
                'away_team_score' => null,
            ]);
        $secondWeekFixture = Fixture::factory()
            ->create([
                'home_team_id' => $teamB->id,
                'away_team_id' => $teamA->id,
                'week' => 2,
                'home_team_score' => null,
                'away_team_score' => null,
            ]);

        m::mock(
            FixtureService::class,
            [resolve(FixtureRepositoryInterface::class), resolve(TeamRepositoryInterface::class)]
        )
            ->makePartial()
            ->shouldReceive('rollADice')
            ->andReturn(1);

        $response = $this->json('POST', 'api/fixtures/play-all');

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseMissing('fixtures', ['id' => $firstWeekFixture->id, 'home_team_score' => null])
            ->assertDatabaseMissing('fixtures', ['id' => $secondWeekFixture->id, 'home_team_score' => null]);
    }

    /** @test */
    public function it_should_update_a_fixture()
    {
        $fixture = Fixture::factory()->create();
        $newFixtureData = Fixture::factory()->make()->only(['home_team_score', 'away_team_score']);

        $response = $this->json('PATCH', "api/fixtures/$fixture->id", $newFixtureData);

        $response->assertOk();

        $this->assertDatabaseHas('fixtures', array_merge(['id' => $fixture->id], $newFixtureData));
    }

    /** @test */
    public function it_should_validate_home_team_score_field_when_updating_a_fixture()
    {
        $fixture = Fixture::factory()->create();
        $newFixtureData = Fixture::factory()->make()->only(['home_team_score', 'away_team_score']);

        $invalidHomeScoreValues = [null, -rand(1, 100), [], Str::random()];

        foreach ($invalidHomeScoreValues as $invalidHomeScoreValue) {
            $newFixtureData['home_team_score'] = $invalidHomeScoreValue;

            $response = $this->json('PATCH', "api/fixtures/$fixture->id", $newFixtureData);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors('home_team_score');
        }
    }

    /** @test */
    public function it_should_validate_away_team_score_field_when_updating_a_fixture()
    {
        $fixture = Fixture::factory()->create();
        $newFixtureData = Fixture::factory()->make()->only(['home_team_score', 'away_team_score']);

        $invalidAwayScoreValues = [null, -rand(1, 100), [], Str::random()];

        foreach ($invalidAwayScoreValues as $invalidAwayScoreValue) {
            $newFixtureData['away_team_score'] = $invalidAwayScoreValue;

            $response = $this->json('PATCH', "api/fixtures/$fixture->id", $newFixtureData);

            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->assertJsonValidationErrors('away_team_score');
        }
    }
}

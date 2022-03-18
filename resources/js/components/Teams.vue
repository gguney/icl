<template>
    <div class="rounded shadow-lg min-w-full mb-2">
        <div class="px-6 py-4 border-b">
            <span class="font-bold text-2xl">
                Teams
            </span>
            <span class="ml-2" v-if="fixtures">
                <t-button
                    v-if="currentWeek < maxWeekCount"
                    class="mr-2"
                    @click="playNextWeek"
                >
                    Play Next Week
                </t-button>
                <t-button
                    v-if="currentWeek < maxWeekCount"
                    class="mr-2"
                    type="success"
                    @click="playAll"
                >
                    Play All Weeks
                </t-button>
                <t-button
                    class="mr-2"
                    type="danger"
                    @click="askResetFixtures"
                >
                    Reset
                </t-button>
            </span>
            <span class="text-red-500" v-else>First, "Generate" fixtures to see Play the fixture.</span>
        </div>
        <div class="px-6 pt-4 pb-2">
            <div>
                <strong>Current Week: </strong> {{ currentWeek }}
                <strong>Last Week: </strong> {{ maxWeekCount }}
            </div>
            <div class="py-4">
                <t-input label="Search" placeholder="Write team name to search" v-model="search" />
            </div>
            <table class="t-table">
                <thead>
                <tr class="border-b">
                    <th>Rank</th>
                    <th>Team</th>
                    <th>PTS</th>
                    <th>P</th>
                    <th>W</th>
                    <th>D</th>
                    <th>L</th>
                    <th>GD</th>
                    <th>Goals/M</th>
                    <th>Prediction</th>
                    <th>Matches</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="team in filteredTeams" :key="team.id" class="border-b">
                    <td>{{ team.rank }}</td>
                    <td>
                        <div class="font-bold">
                            {{ team.name }}
                            <div>
                                <img src="/images/power.png" class="power-logo" alt="power logo" />
                                <span>{{ team.power }}</span>
                            </div>
                        </div>
                        <div class="text-gray-400">{{ team.stadium.name }}</div>
                    </td>
                    <td>
                        {{ team.points }}
                    </td>
                    <td>{{ team.played }}</td>
                    <td>{{ team.win }}</td>
                    <td>{{ team.draw }}</td>
                    <td>{{ team.loose }}</td>
                    <td>{{ team.goalDifference }}</td>
                    <td>
                        {{ team.scored_goals_per_match.toFixed(2) }}
                        <br>
                        {{ team.conceded_goals_per_match.toFixed(2) }}
                    </td>
                    <td>
                        {{ (team.prediction / predictionSums * 100).toFixed(2) }} %
                    </td>
                    <td>
                        <span v-for="fixture in getTeamFixtures(team)" :key="fixture.id">
                            <t-score-badge :type="getScoreBadgeType(team, fixture)">
                                <span v-if="team.id === fixture.home_team_id">
                                    {{ fixture.home_team_score }} - {{ fixture.away_team_score }}
                                </span>
                                <span v-else>{{ fixture.away_team_score }} - {{ fixture.home_team_score }}</span>
                            </t-score-badge>
                        </span>
                    </td>
                </tr>
                <tr v-if="filteredTeams.length === 0 && teams">
                    <td colspan="3">No teams found.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { index as indexTeams } from '../api/team'
import { playAll, playNextWeek, resetAllFixtures } from '../api/fixture'
import TScoreBadge from './TScoreBadge';
import TButton from './TButton';
import TInput from './TInput';

export default {
    components: { TButton, TInput, TScoreBadge },
    data() {
        return {
            teams: null,
            search: null,
            predictionSums: null,
            currentWeek: null,
            powerSum: null,
            maxWeekCount: null,
            fixtures: null,
        }
    },
    computed: {
        filteredTeams() {
            if (!this.search) {
                return (this.teams || [])
            }

            return (this.teams.filter(team => team.name.toLowerCase().includes(this.search.toLowerCase())))
        }
    },
    async mounted() {
        await this.fetchTeams()
    },
    methods: {
        async fetchTeams() {
            this.teams = []
            this.teams = (await indexTeams()).data.teams
            this.currentWeek = this.teams[0].played || 1;
            this.maxWeekCount = (this.teams.length - 1) * 2;
            this.powerSum = this.teams.reduce((sum, current) => sum + current.power, 0)
            this.setTeamStats()
        },
        setFixtures(fixtures){
            this.fixtures = fixtures
        },
        setTeamStats() {
            this.predictionSums = 0;
            const leaderPoint = this.teams[0].points

            this.teams.map((team, index) => {
                const played = team.played || 1
                let leftWeekCount = this.maxWeekCount - played

                team.rank = index + 1
                team.scored_goals_per_match = team.scoredGoals / played
                team.conceded_goals_per_match = team.concededGoals / played
                team.average_points = team.points / played

                if (team.played === 0) {
                    team.prediction = team.power / this.powerSum
                } else if (this.currentWeek === this.maxWeekCount) {
                    team.prediction = team.rank === 1 ? 1 : 0
                } else {
                    if (team.points + 3 * leftWeekCount > leaderPoint) {
                        team.prediction = team.points +
                            team.average_points * leftWeekCount +
                            (team.power / this.powerSum) * 100
                    } else {
                        team.prediction = 0
                    }
                }

                this.predictionSums += team.prediction
            })
        },
        getTeamFixtures(team) {
            return (team.home_team_fixtures.concat(team.away_team_fixtures)).sort((a, b) => a.id - b.id)
        },
        getScoreBadgeType(team, fixture) {
            if (!fixture.home_team_score && !fixture.away_team_score) {
                return ''
            }

            if (fixture.home_team_score === fixture.away_team_score) {
                return 'gray'
            }

            if (team.id === fixture.home_team_id) {
                return fixture.home_team_score > fixture.away_team_score ? 'success' : 'danger'
            } else {
                return fixture.away_team_score > fixture.home_team_score ? 'success' : 'danger'
            }
        },
        playNextWeek() {
            playNextWeek()
                .then(response => {
                    this.fetchTeams()
                    this.$emit('played')
                }).catch(error => {
                    console.log(error)
                })
        },
        playAll() {
            playAll()
                .then(response => {
                    this.fetchTeams()
                    this.$emit('played')
                }).catch(error => {
                    console.log(error)
                })
        },
        askResetFixtures() {
            if (confirm('You are about to delete fixtures. Do you want to proceed?')) {
                this.resetAllFixtures()
            }
        },
        resetAllFixtures() {
            resetAllFixtures()
                .then(response => {
                    this.fetchTeams()
                    this.$emit('played')
                }).catch(error => {
                    console.log(error)
                }
            )
        }
    }
}
</script>

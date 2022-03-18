<template>
    <div class="rounded shadow-lg min-w-full mb-2">
        <div class="px-6 py-4 border-b">
            <span class="font-bold text-2xl mr-2">Fixtures</span>
            <t-button @click="askGenerateFixtures">Generate</t-button>
        </div>
        <div class="px-6 pt-4 pb-2" v-if="fixtures">
            <div>
                <strong>Total Matches: {{ fixtures.length }}</strong>
            </div>
            <div >
                <div class="col-span-3 border-b py-2" v-for="fixture in fixtures" :key="fixture.id">
                    <div class="grid grid-cols-4 gap-4">
                        <div>
                            <h6 class="font-bold">Week - {{ fixture.week }}</h6>
                            <div class="fw-bold">{{ fixture.stadium.name }}</div>
                        </div>
                            <div>
                                {{ fixture.home_team.name }} (Home)
                                <t-input v-if="fixture.is_played" v-model="fixture.home_team_score" />
                            </div>
                        <div>
                            {{ fixture.away_team.name }}
                            <t-input v-if="fixture.is_played" v-model="fixture.away_team_score" />
                        </div>
                        <div class="pt-6">
                            <t-button v-if="fixture.is_played" @click="updateFixture(fixture)">Update</t-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TButton from './TButton'
import { generate, index, update } from '../api/fixture'
import TInput from './TInput';

export default {
    components: { TInput, TButton },
    data() {
        return {
            fixtures: null
        }
    },
    async created() {
        await this.fetchAllFixtures()

        if(this.fixtures.length > 0){
            this.$emit('fixturesFetched', this.fixtures)
        }
    },
    methods: {
        askGenerateFixtures(){
            if (confirm('You are about to regenerate fixtures. Do you want to proceed?')) {
                this.generateFixtures()
            }
        },
        generateFixtures() {
            generate()
                .then(response => {
                    this.fetchAllFixtures()
                    this.$emit('fixturesFetched', this.fixtures)
                }).catch(error => {
                    console.log(error)
                })
        },
        async fetchAllFixtures() {
            await index()
                .then(response => {
                    this.fixtures = []
                    this.fixtures = response.data.fixtures
                }).catch(error => {
                    console.log(error)
                })
        },
        updateFixture(fixture) {
            update(
                fixture.id,
                { home_team_score: fixture.home_team_score, away_team_score: fixture.away_team_score }
            )
                .then(response => {
                    this.$emit('fixturesFetched', this.fixtures)
                }).catch(error => {
                    console.log(error)
                })
        }
    }
}
</script>

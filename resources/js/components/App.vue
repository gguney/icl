<template>
    <div>
        <teams @played="onPlayed" ref="teams"></teams>
        <fixtures class="pt-4" ref="fixtures" @fixturesFetched="onFixturesFetched"></fixtures>
    </div>
</template>

<script>
import Teams from './Teams'
import Fixtures from './Fixtures'

export default {
    components: { Teams, Fixtures },
    methods: {
        onPlayed(){
            this.$refs.fixtures.fetchAllFixtures()
        },
        onFixturesFetched(fixtures){
            this.$refs.teams.setFixtures(fixtures)
            this.$refs.teams.fetchTeams()
        }
    }
}
</script>


<template>
  <q-page class="q-pa-xl flex gap-40 items-center">
    <div class="section">
      <h2 class="text-grey text-body1">Team</h2>
      <div class="teams flex gap-20" v-if="teams && teams.length > 0">
        <q-card v-for="team in teams" :key="team.id" class="team q-py-md q-px-lg flex justify-between items-center">
          <q-card-section horizontal>
            <q-icon size="40px" color="primary" :name="team.icon" class="q-mr-lg" />
            <div>
              <h3 class="text-body1 q-my-none">{{ strMaxLenght(team.title, 13) }}</h3>
              <p class="text-caption q-my-none">{{ team.projects.length + ' projet' + (team.projects.length > 1 ? 's' :
        '') }}</p>
            </div>
          </q-card-section>
          <q-card-section class="q-pa-none">
            <q-btn icon="more_horiz" text-color="grey-8" flat />
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { strMaxLenght } from 'src/helpers/formatting'

export default {
  name: 'IndexPage',
  setup() {
    return {
      strMaxLenght,
    }
  },
  data() {
    return {
      teams: [
        {
          id: 1,
          title: 'Team 1  jhcvdskjd fnslkn flks fls ',
          icon: 'sym_o_emoji_events',
          projects: [
            { title: 'Project 1' },
            { title: 'Project 2' },
            { title: 'Project 3' },
          ],
        },
        {
          id: 2,
          title: 'Team 2',
          icon: 'sym_o_palette',
          projects: [
            { title: 'Project 1' },
            { title: 'Project 2' },
            { title: 'Project 3' },
          ],
        },
        {
          id: 3,
          title: 'Team 3',
          icon: 'sym_o_bolt',
          projects: [
            { title: 'Project 1' },
            { title: 'Project 2' },
            { title: 'Project 3' },
          ],
        },
      ],
    };
  },
}
</script>

<style lang="scss" scoped>
.team {
  width: 300px;
}
</style>

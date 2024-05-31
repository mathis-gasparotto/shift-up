<template>
  <q-card class="team-card q-py-md q-px-lg flex justify-between items-center cursor-pointer" @click="click()">
    <q-card-section horizontal>
      <q-icon v-if="team.icon" size="40px" color="primary" :name="team.icon" class="q-mr-lg" />
      <div>
        <h3 class="text-body1 q-my-none">{{ strMaxLenght(team.name, 13) }}</h3>
        <p class="text-caption q-my-none">{{ team.projects.length + ' projet' + (team.projects.length > 1 ? 's' :
    '') }}</p>
      </div>
    </q-card-section>
    <q-card-section class="q-pa-none">
      <q-btn icon="more_horiz" text-color="grey-8" flat @click.stop="openSettings()" />
    </q-card-section>
  </q-card>
</template>

<script>
import { strMaxLenght } from 'src/helpers/formatting'

export default {
  name: 'TeamCard',
  props: {
    team: {
      type: Object,
      required: true
    },
    link: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    return {
      strMaxLenght
    }
  },
  methods: {
    openSettings() {
      console.log('open settings')
    },
    click() {
      if (this.link) {
        this.$router.push({ name: 'team', params: { teamId: this.team.id, } })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.team-card {
  width: 300px;
}
</style>

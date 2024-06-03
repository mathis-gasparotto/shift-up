<template>
  <q-page class="column">
    <MainBreadcrumps :loading="teamLoading || projectLoading" :team-name="team.name" :project-name="project.name" />
    <h1 class="text-h2 q-mt-xl q-mb-lg">
      <q-skeleton v-if="projectLoading" />
      {{ project.name }}
    </h1>
  </q-page>
</template>

<script>
import { strMaxLenght, durationFromDateTime } from 'src/helpers/formatting'
import { displayError } from 'src/helpers/translatting'
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'

export default {
  components: {
    MainBreadcrumps
  },
  setup() {
    return {
      strMaxLenght,
      durationFromDateTime
    }
  },
  data() {
    return {
      projectLoading: true,
      teamLoading: true,
      team: {},
      project: {}
    }
  },
  created() {
    this.reloadData()
  },
  methods: {
    reloadData() {
      this.teamLoading = true
      this.projectLoading = true
      this.$resources.teams.get(this.$route.params.teamId).then(res => {
        this.team = res
        this.teamLoading = false
      }).catch((err) => {
        displayError(err)
        this.teamLoading = false
      })
      this.$resources.projects.get(this.$route.params.projectId).then(res => {
        this.project = res
        this.projectLoading = false
      }).catch((err) => {
        displayError(err)
        this.projectLoading = false
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.project-card {
  width: 300px;
}

.project-img {
  border-radius: 4px;
}
</style>

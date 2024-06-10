<template>
  <q-page class="column">
    <MainBreadcrumps :loading="teamLoading || projectLoading" :team-name="team.name" :project-name="project.name" />
    <div class="q-mt-xl q-mb-lg row item-center justify-between">
      <h1 class="text-h2 q-my-none">
        <q-skeleton v-if="projectLoading" width="150px" />
        {{ project.name }}
      </h1>
      <q-btn icon="more_horiz" text-color="grey-8" flat @click="openSettings" :disable="projectLoading" />
      <ProjectSettingsModal v-if="!projectLoading" ref="projectSettingsModal" :project="project" />
    </div>
    <div class="w-100 q-mt-xl" v-if="!projectLoading">
      <div class="no-doc-card column items-center q-mx-auto">
        <q-avatar size="150px">
          <img :src="'/src/assets/projects/' + project.picture.contentUrl">
        </q-avatar>
        <h2 class="text-h6 text-center">Your project is empty...</h2>
        <p class="text-subtitle2 text-weight-regular text-center">Lorem ipsum dolor sit amet, consectetur adipiscing
          elit sed.</p>
        <SUBtn label="Generate strategy" />
      </div>
    </div>
  </q-page>
</template>

<script>
import { strMaxLenght, durationFromDateTime } from 'src/helpers/formatting'
import { displayError } from 'src/helpers/translatting'
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import SUBtn from 'src/components/SUBtn.vue'
import ProjectSettingsModal from 'src/components/Projects/ProjectSettingsModal.vue'

export default {
  components: {
    MainBreadcrumps,
    ProjectSettingsModal,
    SUBtn
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
    },
    openSettings() {
      this.$refs.projectSettingsModal.openModal()
    }
  }
}
</script>

<style lang="scss" scoped>
.no-doc-card {
  width: 250px;
}
</style>

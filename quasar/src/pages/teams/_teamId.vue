<template>
  <q-page class="column">
    <MainBreadcrumps :loading="teamLoading" :team-name="team.name" />
    <h1 class="text-h4">
      <q-skeleton v-if="teamLoading" />
      {{ team.name }}
    </h1>
    <h2 class="text-grey text-body1">Projects</h2>
    <ProjectList :loading="projectsLoading" :projects="projects" add-card />
  </q-page>
</template>

<script>
import { strMaxLenght, durationFromDateTime } from 'src/helpers/formatting'
import { displayError } from 'src/helpers/translatting'
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import ProjectList from 'src/components/Projects/ProjectList.vue'

export default {
  components: {
    MainBreadcrumps,
    ProjectList
  },
  setup() {
    return {
      strMaxLenght,
      durationFromDateTime
    }
  },
  data() {
    return {
      projectsLoading: true,
      team: {},
      projects: [
        // {
        //   id: 1,
        //   name: 'Project 1',
        //   updatedAt: '2024-04-30T07:36:16+00:00',
        //   picture: {
        //     contentUrl: 'project-illustration-1.jpg',
        //   }
        // },
        // {
        //   id: 2,
        //   name: 'Project 2',
        //   updatedAt: '2024-04-30T07:36:16+00:00',
        //   picture: {
        //     contentUrl: 'project-illustration-2.jpg',
        //   }
        // },
        // {
        //   id: 3,
        //   name: 'Project 3',
        //   updatedAt: '2024-04-30T07:36:16+00:00',
        //   picture: {
        //     contentUrl: 'project-illustration-3.jpg',
        //   }
        // },
      ]
    }
  },
  created() {
    this.reloadData()
  },
  methods: {
    reloadData() {
      this.teamLoading = true
      this.projectsLoading = true
      this.$resources.teams.get(this.$route.params.teamId).then(res => {
        this.team = res
        this.teamLoading = false
      }).catch((err) => {
        displayError(err)
        this.teamLoading = false
      })
      this.$resources.teams.child(this.$route.params.teamId, 'projects').then(res => {
        this.projects = res.data
        this.projectsLoading = false
      }).catch((err) => {
        displayError(err)
        this.projectsLoading = false
      })
    },
    openTeamSettings(teamId) {
      console.log('Open team settings', teamId)
    },
    goToProject(projectId) {
      console.log('Go to project', projectId)
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

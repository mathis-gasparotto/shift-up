<template>
  <q-page class="column gap-40">
    <div
      class="section action-section"
      v-if="false"
    >
      <q-card
        class="action-card q-py-md q-px-lg cursor-pointer"
        @click="openCreateNewTeam"
      >
        <q-card-section
          horizontal
          class="items-center"
        >
          <q-icon
            size="25px"
            color="black"
            name="sym_o_folder"
            class="q-mr-md"
          />
          <div>
            <h3 class="text-body1 q-my-none">{{ $t('home.createNewTeam') }}</h3>
            <p class="text-caption q-my-none">{{ $t('home.organizeYourProjects') }}</p>
          </div>
        </q-card-section>
      </q-card>
      <CreateNewTeamModal ref="createNewTeamModal" />
    </div>
    <div class="section teams-section">
      <h2 class="text-grey text-body1">{{ $t('home.teams') }}</h2>
      <TeamList
        :loading="teamsLoading"
        :teams="teams"
        @updated="onTeamUpdated"
      />
    </div>
    <div class="section projects-section">
      <h2 class="text-grey text-body1">{{ $t('home.projects') }}</h2>
      <ProjectList
        :loading="projectsLoading"
        :projects="projects"
      />
    </div>
  </q-page>
</template>

<script>
import CreateNewTeamModal from 'src/components/Index/CreateNewTeamModal.vue'
import ProjectList from 'src/components/Projects/ProjectList.vue'
import TeamList from 'src/components/Teams/TeamList.vue'
import { displayError } from 'src/helpers/translatting'

export default {
  components: {
    CreateNewTeamModal,
    ProjectList,
    TeamList
  },
  data() {
    return {
      teamsLoading: true,
      projectsLoading: true,
      teams: [
        // {
        //   id: 1,
        //   name: 'Team 1  jhcvdskjd fnslkn flks fls ',
        //   icon: 'sym_o_emoji_events',
        //   projects: [
        //     { name: 'Project 1' },
        //     { name: 'Project 2' },
        //     { name: 'Project 3' },
        //   ],
        // },
        // {
        //   id: 2,
        //   name: 'Team 2',
        //   icon: 'sym_o_palette',
        //   projects: [
        //     { name: 'Project 1' },
        //     { name: 'Project 2' },
        //     { name: 'Project 3' },
        //   ],
        // },
        // {
        //   id: 3,
        //   name: 'Team 3',
        //   icon: 'sym_o_bolt',
        //   projects: [
        //     { name: 'Project 1' },
        //     { name: 'Project 2' },
        //     { name: 'Project 3' },
        //   ],
        // },
      ],
      projects: [
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
        // {
      ]
    }
  },
  created() {
    this.reloadData()
  },
  methods: {
    reloadData() {
      this.teamsLoading = true
      this.projectsLoading = true
      this.$resources.teams
        .list()
        .then((res) => {
          this.teams = res.data.map((team) => ({
            ...team,
            icon: 'sym_o_group'
          }))
          this.teamsLoading = false
        })
        .catch((err) => {
          displayError(err)
          this.teamsLoading = false
        })
      this.$resources.projects
        .list()
        .then((res) => {
          this.projects = res.data
          this.projectsLoading = false
        })
        .catch((err) => {
          displayError(err)
          this.projectsLoading = false
        })
    },
    onTeamUpdated(team) {
      this.teams = this.teams.map((t) => (t.id === team.id ? team : t))
    },
    openTeamSettings(teamId) {
      console.log('Open team settings', teamId)
    },
    goToTeam(teamId) {
      this.$router.push({ name: 'team', params: { id: teamId } })
    },
    openCreateNewTeam() {
      this.$refs.createNewTeamModal.openModal()
    }
  }
}
</script>

<style lang="scss" scoped>
.action-card {
  width: 300px;
}
</style>

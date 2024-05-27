<template>
  <q-page class="flex gap-40 items-center">
    <div class="section action-section" v-if="false">
      <q-card class="action-card q-py-md q-px-lg cursor-pointer" @click="openCreateNewTeam">
        <q-card-section horizontal class="items-center ">
          <q-icon size="25px" color="black" name="sym_o_folder" class="q-mr-md" />
          <div>
            <h3 class="text-body1 q-my-none">Create a new team</h3>
            <p class="text-caption q-my-none">Organize your projects</p>
          </div>
        </q-card-section>
      </q-card>
      <CreateNewTeamModal ref="createNewTeamModal" />
    </div>
    <div class="section teams-section">
      <h2 class="text-grey text-body1">Team</h2>
      <div class="teams-list flex gap-20" v-if="teams && teams.length > 0">
        <q-card v-for="team in teams" :key="team.id"
          class="team-card q-py-md q-px-lg flex justify-between items-center cursor-pointer" @click="goToTeam(team.id)">
          <q-card-section horizontal>
            <q-icon size="40px" color="primary" :name="team.icon" class="q-mr-lg" />
            <div>
              <h3 class="text-body1 q-my-none">{{ strMaxLenght(team.title, 13) }}</h3>
              <p class="text-caption q-my-none">{{ team.projects.length + ' projet' + (team.projects.length > 1 ? 's' :
      '') }}</p>
            </div>
          </q-card-section>
          <q-card-section class="q-pa-none">
            <q-btn icon="more_horiz" text-color="grey-8" flat @click.stop="openTeamSettings(team.id)" />
          </q-card-section>
        </q-card>
      </div>
    </div>
    <div class="section projects-section">
      <h2 class="text-grey text-body1">Projects</h2>
      <div class="prpjects-list flex gap-20" v-if="projects && projects.length > 0">
        <q-card v-for="project in projects" :key="project.id" class="project-card q-pa-lg cursor-pointer"
          @click="goToProject(project.id)">
          <q-card-section class="q-pa-none q-mb-md">
            <q-img :src="'/projects/' + project.picture.contentUrl" height="150px" fit="cover" rounded
              class="project-img w-100" />
          </q-card-section>
          <q-card-section class="q-pa-none">
            <div>
              <h3 class="text-body1 q-my-none">{{ strMaxLenght(project.title, 13) }}</h3>
              <p class="text-caption q-my-none">Edited {{ durationFromDateTime(project.updatedAt) }}</p>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>
  </q-page>
</template>

<script>
import { strMaxLenght, durationFromDateTime } from 'src/helpers/formatting'
import CreateNewTeamModal from 'src/components/Index/CreateNewTeamModal.vue'

export default {
  components: {
    CreateNewTeamModal
  },
  setup() {
    return {
      strMaxLenght,
      durationFromDateTime
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
      projects: [
        {
          id: 1,
          title: 'Project 1',
          updatedAt: '2024-04-30T07:36:16+00:00',
          picture: {
            contentUrl: 'project-illustration-1.jpg',
          }
        },
        {
          id: 2,
          title: 'Project 2',
          updatedAt: '2024-04-30T07:36:16+00:00',
          picture: {
            contentUrl: 'project-illustration-2.jpg',
          }
        },
        {
          id: 3,
          title: 'Project 3',
          updatedAt: '2024-04-30T07:36:16+00:00',
          picture: {
            contentUrl: 'project-illustration-3.jpg',
          }
        },
      ]
    }
  },
  methods: {
    openTeamSettings(teamId) {
      console.log('Open team settings', teamId)
    },
    goToTeam(teamId) {
      console.log('Go to team', teamId)
    },
    goToProject(projectId) {
      console.log('Go to project', projectId)
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

.team-card {
  width: 300px;
}

.project-card {
  width: 300px;
}

.project-img {
  border-radius: 4px;
}
</style>

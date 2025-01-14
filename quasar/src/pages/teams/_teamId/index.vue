<template>
  <q-page class="column">
    <TeamPlansModal
      :team="team"
      v-if="!teamLoading"
      ref="teamPlansModal"
      @submited="reloadTeam"
    />
    <MainBreadcrumps
      :loading="teamLoading"
      :team-name="team.name"
    />
    <div class="q-mt-xl q-mb-lg column row-xs items-center justify-between">
      <SUEntityTitle
        :title="team.name"
        :loading="teamLoading"
        :edit-loading="editTeamNameLoading"
        @edit="(name) => updateTeamName(name)"
        ref="teamName"
      />
      <div class="flex gap-10 q-mt-lg q-mt-xs-none items-center">
        <SUbtn
          :label="$t('team.details.changePlan')"
          rounded
          color="gradient"
          class="h-content"
          @click="$refs.teamPlansModal.openModal()"
        />
        <q-btn
          v-if="team.deletable"
          icon="delete"
          :label="$t('team.details.deleteBtn')"
          stack
          no-caps
          text-color="grey-9"
          flat
          @click="openDelete"
          :disable="teamLoading"
          class="q-no-hoverable q-pa-xs"
        />
      </div>
    </div>
    <InfoCard
      v-if="showSuccessMessage"
      type="success"
      :title="$t('team.details.subscribeSuccess1')"
      class="w-content q-mx-auto"
      :action-btn-label="$t('team.details.closeSuccess')"
      @actionClick="showSuccessMessage = false"
    >
      <template #content>
        <p class="text-center q-mb-none">
          {{ $t('team.details.subscribeSuccess2') }}
        </p>
      </template>
    </InfoCard>
    <h2 class="text-grey text-body1">{{ $t('team.details.projects') }}</h2>
    <ProjectList
      :loading="projectsLoading"
      :projects="projects"
      add-card
    />
    <TeamDeleteModal
      v-if="!teamLoading && team.deletable"
      ref="teamDeleteModal"
      :team="team"
      @deleted="onDeleteTeam"
    />
  </q-page>
</template>

<script>
import { strMaxLenght, durationFromDateTime } from 'src/helpers/formatting'
import { displayError } from 'src/helpers/translatting'
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import SUEntityTitle from 'src/components/SUEntityTitle.vue'
import ProjectList from 'src/components/Projects/ProjectList.vue'
import TeamDeleteModal from 'src/components/Teams/TeamDeleteModal.vue'
import TeamPlansModal from 'src/components/Teams/TeamPlansModal.vue'
import { errorNotify } from 'src/helpers/notifyHelper'
import SUbtn from 'src/components/SUbtn.vue'
import InfoCard from 'src/components/InfoCard.vue'

export default {
  components: {
    MainBreadcrumps,
    SUEntityTitle,
    ProjectList,
    TeamDeleteModal,
    TeamPlansModal,
    SUbtn,
    InfoCard
  },
  setup() {
    return {
      strMaxLenght,
      durationFromDateTime
    }
  },
  data() {
    return {
      teamLoading: true,
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
      ],
      editTeamNameLoading: false,
      showSuccessMessage: false
    }
  },
  created() {
    if (this.$route.query.subscribeSuccess === 'true') {
      this.showSuccessMessage = true
      this.$router.replace({ query: {} })
    }
    this.reloadData()
  },
  methods: {
    reloadData() {
      this.projectsLoading = true
      this.reloadTeam()
      this.$resources.teams
        .child(this.$route.params.teamId, 'projects')
        .then((res) => {
          this.projects = res.data
          this.projectsLoading = false
        })
        .catch((err) => {
          displayError(err)
          this.projectsLoading = false
        })
    },
    reloadTeam() {
      this.teamLoading = true
      this.$resources.teams
        .get(this.$route.params.teamId)
        .then((res) => {
          this.team = res
          this.teamLoading = false
        })
        .catch((err) => {
          if (err.response && err.response.status === 404) {
            errorNotify(this.$t('team.errorNotFound'))
          } else {
            displayError(err)
          }
          this.$router.push({ name: 'home' })
        })
    },
    updateTeamName(name) {
      this.editTeamNameLoading = true
      this.$resources.teams
        .update(this.team.id, { name })
        .then(() => {
          this.team.name = name
          this.editTeamNameLoading = false
          this.$emitter.emit('reloadNavbar')
        })
        .catch((err) => {
          this.editTeamNameLoading = false
          displayError(err)
          this.$refs.teamName.reset()
        })
    },
    openDelete() {
      if (this.team.deletable) {
        this.$refs.teamDeleteModal.openModal()
      }
    },
    onDeleteTeam() {
      this.$router.push({ name: 'home' })
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

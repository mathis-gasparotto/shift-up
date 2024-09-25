<template>
  <q-page class="column">
    <MainBreadcrumps
      :loading="teamLoading || projectLoading"
      :team-name="team.name"
      :project-name="project.name"
    />
    <div class="q-mt-xl q-mb-lg row item-center justify-between">
      <h1 class="text-h2 q-my-none">
        <q-skeleton
          v-if="projectLoading"
          width="150px"
        />
        <template v-else>{{ project.name }}</template>
      </h1>
      <q-btn
        icon="edit"
        label="Modifier"
        stack
        no-caps
        text-color="grey-9"
        flat
        @click="openSettings"
        :disable="projectLoading"
        class="q-no-hoverable q-pa-xs"
      />
      <ProjectSettingsModal
        v-if="!projectLoading"
        ref="projectSettingsModal"
        :project="project"
        @updated="reloadData"
      />
    </div>
    <div class="w-100 q-mt-xl">
      <div
        class="no-doc-card column items-center q-mx-auto"
        v-if="!projectLoading && (!project.lastDocuments || project.lastDocuments.length <= 0)"
      >
        <q-avatar size="150px">
          <img :src="'/src/assets/projects/' + project.picture.contentUrl" />
        </q-avatar>
        <h2 class="text-h6 text-center">{{ $t('project.details.voidProjectTitle') }}</h2>
        <p class="text-subtitle2 text-weight-regular text-center">
          {{ $t('project.details.voidProjectText') }}
        </p>
        <SUbtn :label="$t('project.details.generate')" />
      </div>
      <ProjectDocumentList
        v-else
        :documents="project.lastDocuments || []"
        :project="project"
        :loading="projectLoading"
      />
    </div>
  </q-page>
</template>

<script>
import { strMaxLenght, durationFromDateTime } from 'src/helpers/formatting'
import { displayError } from 'src/helpers/translatting'
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import SUbtn from 'src/components/SUbtn.vue'
import ProjectSettingsModal from 'src/components/Projects/ProjectSettingsModal.vue'
import ProjectDocumentList from 'src/components/Projects/ProjectDocuments/ProjectDocumentList.vue'

export default {
  components: {
    MainBreadcrumps,
    ProjectSettingsModal,
    SUbtn,
    ProjectDocumentList
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
      this.$resources.teams
        .get(this.$route.params.teamId)
        .then((res) => {
          this.team = res
          this.teamLoading = false
        })
        .catch((err) => {
          displayError(err)
          this.teamLoading = false
        })
      this.$resources.projects
        .get(this.$route.params.projectId)
        .then((res) => {
          this.project = res
          this.projectLoading = false
        })
        .catch((err) => {
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

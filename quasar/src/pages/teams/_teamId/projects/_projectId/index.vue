<template>
  <q-page class="column">
    <MainBreadcrumps
      :loading="teamLoading || projectLoading"
      :team-name="team.name"
      :project-name="project.name"
    />
    <div class="q-mt-xl q-mb-lg column row-xs item-center justify-between">
      <h1 class="text-h2 q-my-none">
        <q-skeleton
          v-if="projectLoading"
          width="150px"
        />
        <template v-else>{{ project.name }}</template>
      </h1>
      <div class="flex gap-10 q-mt-lg q-mt-xs-none">
        <q-btn
          icon="refresh"
          :label="$t('project.details.regenerateBtn')"
          stack
          no-caps
          text-color="grey-9"
          flat
          @click="openRegenerate"
          :disable="projectLoading"
          class="q-no-hoverable q-pa-xs"
        />
        <q-btn
          icon="edit"
          :label="$t('project.details.editBtn')"
          stack
          no-caps
          text-color="grey-9"
          flat
          @click="openSettings"
          :disable="projectLoading"
          class="q-no-hoverable q-pa-xs"
        />
      </div>
      <ProjectSettingsModal
        v-if="!projectLoading"
        ref="projectSettingsModal"
        :project="project"
        @updated="reloadData"
      />
      <ProjectRegenerateModal
        v-if="!projectLoading"
        ref="projectRegenerateModal"
        :project="project"
        @generated="reloadData"
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
        <SUbtn
          :label="$t('project.details.generate')"
          @click="openRegenerate"
        />
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
import ProjectRegenerateModal from 'src/components/Projects/ProjectRegenerateModal.vue'
import ProjectDocumentList from 'src/components/Projects/ProjectDocuments/ProjectDocumentList.vue'

export default {
  components: {
    MainBreadcrumps,
    ProjectSettingsModal,
    ProjectRegenerateModal,
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
  watch: {
    $route() {
      this.reloadData()
    }
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
    },
    openRegenerate() {
      this.$refs.projectRegenerateModal.openModal()
    }
  }
}
</script>

<style lang="scss" scoped>
.no-doc-card {
  width: 250px;
}
</style>

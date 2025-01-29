<template>
  <q-page class="column">
    <MainBreadcrumps
      :loading="teamLoading || projectLoading || documentLoading"
      :team-name="team.name"
      :project-name="project.name"
      :project-document-name="title"
    />
    <div class="q-mt-xl q-mb-lg row item-center justify-between">
      <h1 class="text-h2 q-my-none">
        {{ title }}
      </h1>
      <q-btn
        v-if="team.isPremium"
        icon="sym_o_download"
        :label="$t('download')"
        stack
        no-caps
        text-color="grey-9"
        flat
        @click="downloadDocument"
        :loading="downloadLoading"
        :disable="documentLoading"
        class="q-no-hoverable q-pa-xs"
      />
      <!-- <q-btn
        icon="edit"
        label="Modifier"
        stack
        no-caps
        text-color="grey-9"
        flat
        @click="editMode"
        :disable="documentLoading"
        class="q-no-hoverable q-pa-xs"
      /> -->
    </div>
    <q-skeleton
      type="rect"
      height="500px"
      v-if="documentLoading || teamLoading || projectLoading"
    />
    <q-img
      v-else
      loading="lazy"
      :alt="`project-document_${$route.params.documentName}`"
      :src="`${documentImageFolderPath}/teams/${team.id}/documents/${document['@type'].toLowerCase()}-${document.id}.jpg`"
    />
  </q-page>
</template>

<script>
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import { displayError } from 'src/helpers/translatting'
import { errorNotify } from 'src/helpers/notifyHelper'
export default {
  components: {
    MainBreadcrumps
  },
  data() {
    return {
      documentLoading: true,
      teamLoading: true,
      projectLoading: true,
      document: {},
      team: {},
      project: {},
      documentNamesAccepted: ['business-model-canvas', 'buyer-persona', 'competitor-analysis', 'golden-triangle', 'marketing-mix-4', 'marketing-mix-5', 'pestel', 'smart', 'stp', 'swot'],
      downloadLoading: false,
      documentImageFolderPath: ''
    }
  },
  created() {
    if (!this.documentNamesAccepted.includes(this.$route.params.documentName)) {
      return this.$router.push({
        name: 'project',
        params: { teamId: this.$route.params.teamId, projectId: this.$route.params.projectId }
      })
    }
    this.documentImageFolderPath = process.env.API_URL + process.env.API_TEAM_DOCUMENTS_PATH
    this.reloadData()
  },
  computed: {
    title() {
      switch (this.$route.params.documentName) {
        case 'business-model-canvas':
          return 'Business Model Canvas'
        case 'buyer-persona':
          return 'Buyer Persona'
        case 'competitor-analysis':
          return 'Analyse de la concurrence'
        case 'golden-triangle':
          return "Triangle d'or"
        case 'marketing-mix-4':
          return '4P'
        case 'marketing-mix-5':
          return '5P'
        case 'pestel':
          return 'PESTEL'
        case 'smart':
          return 'SMART'
        case 'stp':
          return 'STP'
        case 'swot':
          return 'SWOT'
        default:
          return ''
      }
    },
    apiRoute() {
      switch (this.$route.params.documentName) {
        case 'business-model-canvas':
          return 'business_model_canvas'
        case 'buyer-persona':
          return 'buyer_personas'
        case 'competitor-analysis':
          return 'competitor_analyses'
        case 'golden-triangle':
          return 'golden_triangles'
        case 'marketing-mix-4':
          return 'marketing_mix_4s'
        case 'marketing-mix-5':
          return 'marketing_mix_5s'
        case 'pestel':
          return 'pestels'
        case 'smart':
          return 'smarts'
        case 'stp':
          return 'stps'
        case 'swot':
          return 'swots'
        default:
          return ''
      }
    },
    resourceName() {
      switch (this.$route.params.documentName) {
        case 'business-model-canvas':
          return 'businessModelCanvas'
        case 'buyer-persona':
          return 'buyerPersonas'
        case 'competitor-analysis':
          return 'competitorAnalyses'
        case 'golden-triangle':
          return 'goldenTriangles'
        case 'marketing-mix-4':
          return 'marketingMix4s'
        case 'marketing-mix-5':
          return 'marketingMix5s'
        case 'pestel':
          return 'pestels'
        case 'smart':
          return 'smarts'
        case 'stp':
          return 'stps'
        case 'swot':
          return 'swots'
        default:
          return ''
      }
    }
  },
  methods: {
    reloadData() {
      this.documentLoading = true
      this.$resources.teams
        .get(this.$route.params.teamId)
        .then((team) => {
          this.team = team
          this.teamLoading = false
        })
        .catch((error) => {
          if (error.response && error.response.status === 404) {
            errorNotify(this.$t('team.errorNotFound'))
          } else {
            displayError(error)
          }
          this.$router.push({ name: 'home' })
        })
      this.$resources.projects
        .get(this.$route.params.projectId)
        .then((project) => {
          this.project = project
          this.projectLoading = false
        })
        .catch((error) => {
          if (error.response && error.response.status === 404) {
            errorNotify(this.$t('project.errorNotFound'))
          } else {
            displayError(error)
          }
          this.$router.push({ name: 'team', params: { teamId: this.$route.params.teamId } })
        })
      this.$resources.projects
        .childItem(this.$route.params.projectId, this.apiRoute + '/last')
        .then((document) => {
          this.document = document
          this.documentLoading = false
        })
        .catch((error) => {
          if (error.response && error.response.status === 404) {
            errorNotify(this.$t('document.errorNotFound'))
          } else {
            displayError(error, $t('document.defaultError'))
          }
          this.$router.push({
            name: 'project',
            params: { teamId: this.$route.params.teamId, projectId: this.$route.params.projectId }
          })
        })
    },
    editMode() {
      console.log('editMode')
    },
    downloadDocument() {
      this.downloadLoading = true
      this.$resources[this.resourceName]
        .createChild(this.document.id, 'download', {})
        .then((response) => {
          window.open(response.path, '_blank')
        })
        .catch((error) => {
          displayError(error)
        })
        .finally(() => {
          this.downloadLoading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped></style>

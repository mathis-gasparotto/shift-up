<template>
  <q-page class="column">
    <MainBreadcrumps :loading="teamLoading || projectLoading || documentLoading" :team-name="team.name"
      :project-name="project.name" :project-document-name="title" />
    <div class="q-mt-xl q-mb-lg row item-center justify-between">
      <h1 class="text-h2 q-my-none">
        <q-skeleton v-if="documentLoading" width="150px" />
        <template v-else>{{ title }}</template>
      </h1>
      <q-btn icon="edit" label="Modifier" stack no-caps text-color="grey-9" flat @click="editMode"
        :disable="documentLoading" class="q-no-hoverable q-pa-xs" />
    </div>
    <div class="w-100 q-mt-xl">
      Yoooo
    </div>
  </q-page>
</template>

<script>
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import { displayError } from 'src/helpers/translatting'

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
      documentNamesAccepted: [
        'business-model-canvas',
        'buyer-persona',
        'competitor-analysis',
        'golden-triangle',
        'marketing-mix-4',
        'marketing-mix-5',
        'pestel',
        'smart',
        'stp',
        'swot'
      ]
    }
  },
  created() {
    if (!this.documentNamesAccepted.includes(this.$route.params.documentName)) {
      return this.$router.push({ name: 'project', params: { teamId: this.$route.params.teamId, projectId: this.$route.params.projectId } })
    }
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
          return 'Triangle d\'or'
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
    }
  },
  methods: {
    reloadData() {
      this.documentLoading = true
      this.$resources.teams.get(this.$route.params.teamId)
        .then((team) => {
          this.team = team
        })
        .catch((error) => {
          displayError(error)
        })
        .finally(() => {
          this.teamLoading = false
        })
      this.$resources.projects.get(this.$route.params.projectId)
        .then((project) => {
          this.project = project
        })
        .catch((error) => {
          displayError(error)
        })
        .finally(() => {
          this.projectLoading = false
        })
      this.$resources.projects.child(this.$route.params.projectId, this.apiRoute + '/last')
        .then((document) => {
          this.document = document
        })
        .catch((error) => {
          displayError(error)
        })
        .finally(() => {
          this.documentLoading = false
        })
    },
    editMode() {
      console.log('editMode')
    }
  }
}
</script>

<style lang="scss" scoped></style>

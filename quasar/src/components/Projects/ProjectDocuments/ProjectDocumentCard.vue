<template>
  <q-card :class="'project-card q-pa-lg' + (linked && documentRoute ? ' cursor-pointer' : '')" @click="click()">
    <q-card-section class="q-pa-none q-mb-md">
      <!-- <q-img :src="'/src/assets/projects/' + project.picture.contentUrl" height="150px" fit="cover" rounded
        class="project-img w-100" /> -->
    </q-card-section>
    <q-card-section class="q-pa-none">
      <div>
        <h3 class="text-body1 q-my-none">{{ strMaxLenght(title, 25) }}</h3>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import { strMaxLenght } from 'src/helpers/formatting'

export default {
  name: 'ProjectDocumentCard',
  props: {
    project: {
      type: Object,
      required: true
    },
    document: {
      type: Object,
      required: true
    },
    linked: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    return {
      strMaxLenght
    }
  },
  computed: {
    documentRoute() {
      const params = { teamId: this.project.team.id, projectId: this.project.id }
      let routeName
      let routeParamDocumentId
      switch (this.document['@type']) {
        case 'BusinessModelCanvas':
          routeName = 'businessModelCanvas'
          routeParamDocumentId = 'businessModelCanvasId'
          break
        case 'BuyerPersona':
          routeName = 'buyerPersona'
          routeParamDocumentId = 'buyerPersonaId'
          break
        case 'CompetitorAnalysis':
          routeName = 'competitorAnalysis'
          routeParamDocumentId = 'competitorAnalysisId'
          break
        case 'GoldenTriangle':
          routeName = 'goldenTriangle'
          routeParamDocumentId = 'goldenTriangleId'
          break
        case 'MarketingMix4':
          routeName = 'marketingMix4'
          routeParamDocumentId = 'marketingMix4Id'
          break
        case 'MarketingMix5':
          routeName = 'marketingMix5'
          routeParamDocumentId = 'marketingMix5Id'
          break
        case 'PESTEL':
          routeName = 'pestel'
          routeParamDocumentId = 'pestelId'
          break
        case 'SMART':
          routeName = 'smart'
          routeParamDocumentId = 'smartId'
          break
        case 'STP':
          routeName = 'stp'
          routeParamDocumentId = 'stpId'
          break
        case 'SWOT':
          routeName = 'swot'
          routeParamDocumentId = 'swotId'
          break
      }

      if (!routeName || !routeParamDocumentId) return null

      params[routeParamDocumentId] = this.document.id
      return { name: routeName, params }
    },
    title() {
      switch (this.document['@type']) {
        case 'BusinessModelCanvas':
          return 'Business Model Canvas'
        case 'BuyerPersona':
          return 'BuyerPersona'
        case 'CompetitorAnalysis':
          return 'Competitor Analysis'
        case 'GoldenTriangle':
          return 'Golden Triangle'
        case 'MarketingMix4':
          return '4P'
        case 'MarketingMix5':
          return '5P'
        case 'PESTEL':
          return 'PESTEL'
        case 'SMART':
          return 'SMART'
        case 'STP':
          return 'STP'
        case 'SWOT':
          return 'SWOT'
        default:
          return ''
      }
    }
  },
  methods: {
    click() {
      if (this.linked && this.documentRoute) {
        this.$router.push(this.documentRoute)
      }
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

<template>
  <component
    :is="linked && documentRoute ? 'router-link' : 'div'"
    :to="documentRoute"
    class="no-link-style"
  >
    <q-card class="project-document-card q-pa-lg">
      <q-card-section class="q-pa-none q-mb-md">
        <!-- <q-img :src="'/src/assets/projects/' + project.picture.contentUrl" height="150px" fit="cover" rounded
        class="project-document-img w-100" /> -->
        <q-skeleton
          type="rect"
          class="project-document-img"
          height="150px"
        />
      </q-card-section>
      <q-card-section class="q-pa-none">
        <div>
          <h3 class="text-body1 q-my-none">{{ strMaxLenght(title, 25) }}</h3>
        </div>
      </q-card-section>
    </q-card>
  </component>
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
      switch (this.document['@type']) {
        case 'BusinessModelCanvas':
          params.documentName = 'business-model-canvas'
          break
        case 'BuyerPersona':
          params.documentName = 'buyer-persona'
          break
        case 'CompetitorAnalysis':
          params.documentName = 'competitor-analysis'
          break
        case 'GoldenTriangle':
          params.documentName = 'golden-triangle'
          break
        case 'MarketingMix4':
          params.documentName = 'marketing-mix-4'
          break
        case 'MarketingMix5':
          params.documentName = 'marketing-mix-5'
          break
        case 'PESTEL':
          params.documentName = 'pestel'
          break
        case 'SMART':
          params.documentName = 'smart'
          break
        case 'STP':
          params.documentName = 'stp'
          break
        case 'SWOT':
          params.documentName = 'swot'
          break
      }

      if (!params.documentName) return null

      return { name: 'project-document', params }
    },
    value() {
      switch (this.document['@type']) {
        case 'BusinessModelCanvas':
          return 'business_model_canvas'
        case 'BuyerPersona':
          return 'buyer_persona'
        case 'CompetitorAnalysis':
          return 'competitor_analysis'
        case 'GoldenTriangle':
          return 'golden_triangle'
        case 'MarketingMix4':
          return 'marketing_mix4'
        case 'MarketingMix5':
          return 'marketing_mix5'
        case 'PESTEL':
          return 'pestel'
        case 'SMART':
          return 'smart'
        case 'STP':
          return 'stp'
        case 'SWOT':
          return 'swot'
        default:
          return ''
      }
    },
    title() {
      return this.$t(`document.name.${this.value}`)
    }
  }
}
</script>

<style lang="scss" scoped>
.project-document-card {
  width: 300px;
}

.project-document-img {
  border-radius: 4px;
}
</style>

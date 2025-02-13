<template>
  <div>
    <div class="project-document-list flex column row-sm items-center gap-20">
      <ProjectDocumentCardSkeleton v-if="loading" />
      <template v-else-if="documents.length > 0">
        <ProjectDocumentCard
          v-for="document in documents"
          :project="project"
          :key="document.id"
          :document="document"
          linked
        />
      </template>
      <AddCard
        v-if="addCard"
        :text="$t('document.list.newDocument')"
        @cardClick="generateNewDocument"
      />
    </div>
    <div v-if="!addCard && !loading && documents.length === 0">{{ $t('document.list.noDocument') }}</div>
  </div>
</template>

<script>
import ProjectDocumentCardSkeleton from 'src/components/Projects/ProjectDocuments/ProjectDocumentCardSkeleton.vue'
import ProjectDocumentCard from 'src/components/Projects/ProjectDocuments/ProjectDocumentCard.vue'
import AddCard from 'src/components/AddCard.vue'

export default {
  name: 'ProjectDocumentList',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    documents: {
      type: Array,
      required: true
    },
    project: {
      type: Object,
      required: true
    },
    addCard: {
      type: Boolean,
      default: false
    }
  },
  components: {
    ProjectDocumentCardSkeleton,
    ProjectDocumentCard,
    AddCard
  },
  methods: {
    generateNewDocument() {
      console.log('generateNewDocument')
    }
  }
}
</script>

<style lang="scss" scoped></style>

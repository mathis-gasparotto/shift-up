<template>
  <div>
    <q-skeleton v-if="loading" size="md" />
    <Breadcrumbs v-else :items="items" />
  </div>
</template>

<script>
import Breadcrumbs from 'src/components/Breadcrumbs.vue'

export default {
  name: 'MainBreadcrumps',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    teamName: {
      type: String,
      default: ''
    },
    projectName: {
      type: String,
      default: ''
    },
    projectDocumentName: {
      type: String,
      default: ''
    }
  },
  components: {
    Breadcrumbs
  },
  data() {
    return {
      items: []
    }
  },
  watch: {
    projectName() {
      this.setItems()
    },
    teamName() {
      this.setItems()
    },
    projectDocumentName() {
      this.setItems()
    }
  },
  created() {
    this.setItems()
  },
  methods: {
    setItems() {
      if (this.$route.name === 'index') {
        this.items = [
          {
            label: 'Accueil',
            to: { name: 'index' }
          }
        ]
      } else if (this.$route.name === 'team') {
        this.items = [
          {
            label: 'Accueil',
            to: { name: 'index' }
          },
          {
            label: this.teamName,
            to: this.$route
          }
        ]
      } else if (this.$route.name === 'project') {
        this.items = [
          {
            label: 'Accueil',
            to: { name: 'index' }
          },
          {
            label: this.teamName,
            to: { name: 'team', params: { teamId: this.$route.params.teamId } }
          },
          {
            label: this.projectName,
            to: this.$route
          }
        ]
      } else if (this.$route.name === 'project-create') {
        this.items = [
          {
            label: 'Accueil',
            to: { name: 'index' }
          },
          {
            label: this.teamName,
            to: { name: 'team', params: { teamId: this.$route.params.teamId } }
          },
          {
            label: 'Créer un nouveau projet',
            to: this.$route
          }
        ]
      } else if (this.$route.name === 'project-document') {
        this.items = [
          {
            label: 'Accueil',
            to: { name: 'index' }
          },
          {
            label: this.teamName,
            to: { name: 'team', params: { teamId: this.$route.params.teamId } }
          },
          {
            label: this.projectName,
            to: { name: 'project', params: { teamId: this.$route.params.teamId, projectId: this.$route.params.projectId } }
          },
          {
            label: this.projectDocumentName,
            to: this.$route
          }
        ]
      }
    }
  }
}
</script>

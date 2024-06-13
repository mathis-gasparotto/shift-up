<template>
  <div>
    <div class="projects-list flex gap-20">
      <ProjectCardSkeleton v-if="loading" />
      <template v-else-if="projects.length > 0">
        <ProjectCard v-for="project in projects" :key="project.id" :project="project" linked />
      </template>
      <AddCard v-if="addCard" text="Nouveau projet" @cardClick="createNewProject" />
    </div>
    <div v-if="!addCard && !loading && projects.length === 0">No project</div>
  </div>
</template>

<script>
import ProjectCardSkeleton from 'src/components/Projects/ProjectCardSkeleton.vue'
import ProjectCard from 'src/components/Projects/ProjectCard.vue'
import AddCard from 'src/components/AddCard.vue'

export default {
  name: 'ProjectList',
  props: {
    loading: {
      type: Boolean,
      default: false
    },
    projects: {
      type: Array,
      required: true
    },
    addCard: {
      type: Boolean,
      default: false
    }
  },
  components: {
    ProjectCardSkeleton,
    ProjectCard,
    AddCard
  },
  methods: {
    createNewProject() {
      this.$router.push({ name: 'project-create', params: { teamId: this.$route.params.teamId } })
    }
  }
}
</script>

<style lang="scss" scoped></style>

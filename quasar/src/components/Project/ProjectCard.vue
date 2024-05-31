<template>
  <q-card class="project-card q-pa-lg cursor-pointer" @click="click()">
    <q-card-section class="q-pa-none q-mb-md">
      <q-img :src="'/projects/' + project.picture.contentUrl" height="150px" fit="cover" rounded
        class="project-img w-100" />
    </q-card-section>
    <q-card-section class="q-pa-none">
      <div>
        <h3 class="text-body1 q-my-none">{{ strMaxLenght(project.name, 13) }}</h3>
        <p class="text-caption q-my-none">Edited {{ durationFromDateTime(project.updatedAt) }}</p>
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
import { strMaxLenght, durationFromDateTime } from 'src/helpers/formatting'

export default {
  name: 'ProjectCard',
  props: {
    project: {
      type: Object,
      required: true
    },
    link: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    return {
      strMaxLenght,
      durationFromDateTime
    }
  },
  methods: {
    click() {
      if (this.link) {
        this.$router.push({ name: 'project', params: { teamId: this.project.team.id, projectId: this.project.id } })
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

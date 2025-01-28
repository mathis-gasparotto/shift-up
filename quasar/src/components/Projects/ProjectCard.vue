<template>
  <component
    :is="linked ? 'router-link' : 'div'"
    :to="{ name: 'project', params: { teamId: project.team.id, projectId: project.id } }"
    class="no-link-style"
  >
    <q-card class="project-card q-pa-lg">
      <q-card-section class="q-pa-none q-mb-md">
        <q-img
          :src="`/project_illustrations/${project.picture.contentUrl}`"
          height="150px"
          fit="cover"
          rounded
          class="project-img w-100"
        />
      </q-card-section>
      <q-card-section class="q-pa-none">
        <div>
          <h3 class="text-body1 q-my-none">{{ strMaxLenght(project.name, 13) }}</h3>
          <p class="text-caption q-my-none">
            {{ $t('project.card.edited') + ' ' + durationFromDateTime(project.updatedAt) }}
          </p>
        </div>
      </q-card-section>
    </q-card>
  </component>
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
    linked: {
      type: Boolean,
      default: false
    }
  },
  setup() {
    return {
      strMaxLenght,
      durationFromDateTime
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

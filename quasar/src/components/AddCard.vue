<template>
  <div class="q-pa-lg">
    <q-card class="card cursor-pointer bg-grey-3" @click="$emit('cardClick')" flat>
      <q-card-section class="h-100 flex items-center">
        <q-icon name="ti-plus" size="60px" class="w-100" />
      </q-card-section>
    </q-card>
    <p v-if="text" class="q-mb-none q-mt-md">{{ text }}</p>
  </div>
</template>

<script>
export default {
  name: 'AddCard',
  emits: ['cardClick'],
  props: {
    text: {
      type: String,
      default: ''
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
.card {
  width: 252px;
  height: 166px;
}
</style>

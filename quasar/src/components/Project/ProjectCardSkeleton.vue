<template>
  <q-card class="project-card q-pa-lg cursor-loading">
    <q-card-section class="q-pa-none q-mb-md">
      <q-skeleton type="rect" class="project-img" />
    </q-card-section>
    <q-card-section class="q-pa-none">
      <div>
        <q-skeleton />
        <q-skeleton type="text" />
      </div>
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'ProjectCardSkeleton',
}
</script>

<style lang="scss" scoped>
.project-card {
  width: 300px;
}

.project-img {
  border-radius: 4px;
  height: 150px;
}
</style>

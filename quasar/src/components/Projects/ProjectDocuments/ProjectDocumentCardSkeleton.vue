<template>
  <q-card class="project-document-card q-pa-lg cursor-loading">
    <q-card-section class="q-pa-none q-mb-md">
      <q-skeleton type="rect" class="project-document-img" />
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
  name: 'ProjectDocumentCardSkeleton',
}
</script>

<style lang="scss" scoped>
.project-document-card {
  width: 300px;
}

.project-document-img {
  border-radius: 4px;
  height: 150px;
}
</style>

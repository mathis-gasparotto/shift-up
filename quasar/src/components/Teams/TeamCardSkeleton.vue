<template>
  <q-card class="team-card q-py-md q-px-lg flex justify-between items-center cursor-loading">
    <q-card-section horizontal>
      <q-skeleton type="circle" class="q-mr-lg" />
      <div class="team-card-text">
        <q-skeleton />
        <q-skeleton type="text" />
      </div>
    </q-card-section>
    <q-card-section class="q-pa-none">
      <q-skeleton type="rect" class="team-card-btn" />
    </q-card-section>
  </q-card>
</template>

<script>
export default {
  name: 'TeamCardSkeleton'
}
</script>

<style lang="scss" scoped>
.team-card {
  width: 300px;

  &-text {
    width: 100px;
  }

  &-btn {
    width: 30px;
    height: 30px;
  }
}
</style>

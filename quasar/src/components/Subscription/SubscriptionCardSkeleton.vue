<template>
  <q-skeleton
    type="rect"
    class="subscription-card-skeleton"
  />
</template>
<script>
export default {
  name: 'SubscriptionCardSkeleton'
}
</script>
<style lang="scss" scoped>
.subscription-card-skeleton {
  width: 200px;
  height: 300px;
  border-radius: 4px;
}
</style>

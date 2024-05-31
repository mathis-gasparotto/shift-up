<template>
  <q-breadcrumbs class="text-black text-body2" separator-color="grey" separator=">" gutter="xs" active-color="grey">
    <q-breadcrumbs-el v-for="(item, index) in items" :key="index" :label="item.label"
      :icon="item.icon ? item.icon : undefined" :to="item.to && isLastItem(index) ? item.to : undefined" />
  </q-breadcrumbs>
</template>

<script>
export default {
  name: 'Breadcrumbs',
  props: {
    items: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    isLastItem(index) {
      return index < this.items.length - 1
    }
  }
}
</script>

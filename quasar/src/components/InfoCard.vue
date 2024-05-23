<template>
  <q-card>
    <q-card-section class="flex flex-center">
      <q-icon name="check_circle" :class="'text-' + (type === 'error' ? 'negative' : 'positive') + ' q-mr-sm'"
        size="2em" />
      {{ title }}
    </q-card-section>
    <q-card-section class="q-pt-none" v-if="$slots.content">
      <slot name="content" />
    </q-card-section>
    <q-card-actions align="center" v-if="actionBtnLabel">
      <q-btn flat rounded :to="actionBtnRoute" :label="actionBtnLabel" size="md" no-caps
        class="text-underline q-no-hoverable" />
    </q-card-actions>
  </q-card>
</template>

<script>
export default {
  name: 'InfoCard',
  props: {
    type: {
      type: String,
      default: 'success'
    },
    title: {
      type: String,
      default: ''
    },
    actionBtnLabel: {
      type: String,
      default: ''
    },
    actionBtnRoute: {
      type: [String, Object],
      default: undefined
    }
  }
}
</script>

<style lang="scss" scoped></style>

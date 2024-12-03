<template>
  <q-card :class="{ selected: selected, 'cursor-pointer': clickable }">
    <q-card-section>
      <p>{{ subscription.label }}</p>
    </q-card-section>
    <q-card-section>
      <p>Prix: {{ (subscription.price?.price ?? 0) / 100 }}</p>
    </q-card-section>
    <SUbtn
      v-if="choiceBtn"
      :label="$t('subscription.card.choice')"
      rounded
      color="gradient"
      class="h-content"
      @click="$emit('choice', subscription)"
    />
  </q-card>
</template>
<script>
import SUbtn from 'src/components/SUbtn.vue'

export default {
  name: 'SubscriptionCard',
  emits: ['choice'],
  components: {
    SUbtn
  },
  props: {
    subscription: {
      type: Object,
      required: true
    },
    choiceBtn: {
      type: Boolean,
      default: false
    },
    selected: {
      type: Boolean,
      default: false
    },
    clickable: {
      type: Boolean,
      default: true
    }
  }
}
</script>
<style lang="scss" scoped>
.selected {
  border: 1px solid $primary;
  border-radius: 4px;
}
</style>

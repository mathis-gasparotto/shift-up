<template>
  <q-card
    :class="{ selected: selected, 'cursor-pointer': clickable }"
    class="subscription-card q-pa-lg"
    flat
  >
    <q-card-section class="q-pa-none">
      <p class="text-h6 text-weight-medium q-mb-xs">
        {{ subscription.label }}
      </p>
      <p class="text-body2 text-weight-light q-mb-xs">
        {{ subscription.description }}
      </p>
      <p
        v-if="current && canceled"
        class="text-caption text-accent q-mb-xs"
      >
        {{ $t('subscription.card.canceled', { date: dateToDisplay(canceled) }) }}
      </p>
      <p
        v-else-if="current"
        class="text-caption text-accent q-mb-xs"
      >
        {{ $t('subscription.card.current') }}
      </p>
      <p
        v-else-if="scheduled && !canceled"
        class="text-caption text-accent q-mb-xs"
      >
        {{ $t('subscription.card.scheduled', { date: dateToDisplay(scheduled) }) }}
      </p>
    </q-card-section>
    <q-card-section class="q-pa-none q-mt-lg q-mb-xl">
      <p class="q-mb-none">
        <span class="text-h5 text-weight-medium text-secondary q-mr-xs">{{ $t('price', { price: subscription.price?.recurrence === 'YEAR' ? '~' + (Math.round((subscription.price?.price ?? 0) / 12) / 100).toFixed(2) : (subscription.price?.price ?? 0) / 100 }) }}</span
        >{{ $t('subscription.card.perMonth') }}
      </p>
      <p
        class="text-caption"
        v-if="subscription.price?.recurrence === 'YEAR'"
      >
        <span>{{ $t('price', { price: (subscription.price?.price ?? 0) / 100 }) }}</span
        >{{ $t('subscription.card.perYear') }}
      </p>
    </q-card-section>
    <q-card-section class="q-pa-none q-mb-sm">
      <div
        v-for="(advantage, index) in subscription.advantages"
        :key="index"
        class="flex items-baseline justify-start gap-8 no-wrap"
      >
        <div class="relative-position">
          <q-icon
            name="check_circle"
            color="secondary-light"
            size="xs"
            class="icon-content"
          />
          <div class="absolute bg-secondary icon-background"></div>
        </div>
        <p class="text-body2 text-weight-light q-mb-sm">{{ advantage }}</p>
      </div>
    </q-card-section>
    <div class="flex justify-center">
      <SUbtn
        v-if="choiceBtn"
        :label="$t('subscription.card.choice')"
        rounded
        color="secondary"
        @click="$emit('choice', subscription)"
        :disabled="btnDisabled"
        :loading="btnLoading"
        :outline="!selected"
      />
    </div>
  </q-card>
</template>
<script>
import SUbtn from 'src/components/SUbtn.vue'
import { dateToDisplay } from 'src/helpers/formatting'

export default {
  name: 'SubscriptionCard',
  emits: ['choice', 'btn-click'],
  components: {
    SUbtn
  },
  setup() {
    return {
      dateToDisplay
    }
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
    btnDisabled: {
      type: Boolean,
      default: false
    },
    btnLoading: {
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
    },
    current: {
      type: Boolean,
      default: false
    },
    scheduled: {
      type: [Boolean, Date],
      default: false
    },
    canceled: {
      type: [Boolean, Date],
      default: false
    }
  }
}
</script>
<style lang="scss" scoped>
.subscription-card {
  border-radius: 24px;
  border: 1px solid lightgrey;
  min-width: 200px;
  max-width: 250px;
}
.selected {
  border-color: $primary;
}
.icon-background {
  width: 9px;
  height: 10px;
  top: 8px;
  left: 5px;
}
.icon-content {
  z-index: 1;
}
</style>

<template>
  <q-dialog v-model="open">
    <q-card
      class="card relative q-pa-xl overflow-hidden column no-wrap"
      :style="`width: ${width};max-width: ${width}`"
    >
      <q-icon
        name="close"
        class="absolute-top-right q-mr-xl q-mt-xl z-top cursor-pointer"
        size="25px"
        v-close-popup
      />
      <q-card-section class="q-px-none q-mb-lg">
        <div class="text-h6">{{ title }}</div>
        <div
          v-if="subtitle"
          class="text-body1"
        >
          {{ subtitle }}
        </div>
      </q-card-section>
      <q-card-section class="q-px-none q-pt-none overflow-auto">
        <slot />
      </q-card-section>

      <q-card-actions
        v-if="$slots.buttons"
        :align="buttonsAlignement"
        :class="buttonsAlign === 'full' ? 'w-100' : ''"
      >
        <slot name="buttons" />
      </q-card-actions>
    </q-card>
  </q-dialog>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    title: {
      type: String,
      required: true
    },
    subtitle: {
      type: String,
      required: false
    },
    buttonsAlign: {
      type: String,
      default: 'right'
    },
    width: {
      type: String,
      default: '630px'
    }
  },
  data() {
    return {
      open: false
    }
  },
  computed: {
    buttonsAlignement() {
      switch (this.buttonsAlign) {
        case 'full':
          return null
      }
      return this.buttonsAlign
    }
  }
}
</script>

<style lang="scss" scoped>
.card {
  border-radius: 24px;
  // width: v-bind('props.width');
  // max-width: v-bind('props.width');
}
</style>

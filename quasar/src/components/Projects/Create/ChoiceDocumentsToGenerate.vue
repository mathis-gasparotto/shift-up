<template>
  <div :class="`q-mt-xs grid grid-cols-${mobileColNumber} grid-cols-md-${inModal ? mobileColNumber : desktopColNumber} gap-15`">
    <q-card
      v-for="(option, index) in options"
      @click="
        () => {
          if (!option.disabled) {
            option.selected = !option.selected
          }
        }
      "
      :key="index"
      class="document-checkbox-card col-4 relative-position cursor-pointer bg-grey-2 shadow-0 q-pa-sm"
      :class="{ selected: option.selected, 'card-premium': option.disabled }"
    >
      <q-card-section>
        <q-img
          :src="`/project_documents/${option.value}.svg`"
          class="document-checkbox-card-icon"
        />
      </q-card-section>
      <q-card-section>
        <h3 class="text-h6 q-my-none">{{ $t('document.name.' + option.value) }}</h3>
        <p class="text-body2 q-my-none">{{ $t('document.description.' + option.value) }}</p>
      </q-card-section>
      <q-checkbox
        v-model="option.selected"
        :disable="option.disabled"
        checked-icon="sym_o_check_circle"
        unchecked-icon="radio_button_unchecked"
        size="xl"
        class="absolute check-icon"
        color="secondary"
      />
    </q-card>
  </div>
</template>

<script>
export default {
  name: 'ChoiceDocumentsToGenerate',
  emits: ['update:documentsSelected'],
  props: {
    documentsSelected: {
      type: Array,
      default: () => []
    },
    isPremium: {
      type: Boolean,
      default: false
    },
    mobileColNumber: {
      type: Number,
      default: 2
    },
    desktopColNumber: {
      type: Number,
      default: 3
    },
    inModal: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      options: [
        { value: 'business_model_canvas', selected: false },
        { value: 'buyer_persona', selected: false },
        { value: 'competitor_analysis', selected: false },
        { value: 'golden_triangle', selected: false },
        { value: 'marketing_mix4', selected: false },
        { value: 'marketing_mix5', selected: false },
        { value: 'pestel', selected: false },
        { value: 'smart', selected: false },
        { value: 'stp', selected: false },
        { value: 'swot', selected: false }
      ]
    }
  },
  watch: {
    options: {
      handler() {
        this.documents = this.options.filter((option) => (this.isPremium ? option.selected : !option.disabled && option.selected)).map((option) => option.value)
      },
      deep: true
    }
  },
  created() {
    if (this.isPremium) {
      this.options = this.options.map((option) => ({
        ...option,
        selected: false,
        disabled: false
      }))
    }
    this.options = this.options.map((option) => ({
      ...option,
      selected: this.documentsSelected.includes(option.value)
    }))
  },
  computed: {
    documents: {
      get() {
        return this.documentsSelected
      },
      set(value) {
        this.$emit('update:documentsSelected', value)
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.document-checkbox-card {
  border-radius: 32px;
  border: 1px solid transparent;
  // width: 328px;
  // height: 242px;
  &.selected {
    border-color: $secondary;
    background-color: rgba($secondary, 0.1) !important;
  }
}
.document-checkbox-card-icon {
  width: 100px;
  height: 100px;
  display: inline-block;
  border-radius: 16px;
}
.check-icon {
  right: 10px;
  top: 10px;
}
.card-premium {
  margin-top: calc((14px * 1.5) / 2 + 2px + 1px);
  cursor: not-allowed !important;
  & > * {
    opacity: 0.6;
  }
  &::before {
    content: 'Premium';
    opacity: 1;
    position: absolute;
    background-color: $secondary-light;
    border: 1px solid $secondary;
    top: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    padding: 2px 8px;
    border-radius: 999px;
    font-size: 14px;
  }
}
</style>

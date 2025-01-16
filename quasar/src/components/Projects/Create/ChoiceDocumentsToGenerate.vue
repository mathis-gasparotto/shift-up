<template>
  <div class="q-gutter-sm q-mt-xs column">
    <q-card
      v-for="(option, index) in options"
      @click="option.selected = !option.selected"
      :key="index"
      class="document-checkbox-card col-4 relative-position cursor-pointer bg-grey-2 shadow-0"
      :class="{ selected: option.selected }"
    >
      <q-card-section>
        <q-img
          src="~assets/shift-up-logo.png"
          class="document-checkbox-card-icon"
        />
      </q-card-section>
      <q-card-section>
        {{ $t('document.name.' + option.value) }}
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
  emits: ['update:documentsSelected', 'update:isValid'],
  props: {
    documentsSelected: {
      type: Array,
      default: () => []
    },
    isPremium: {
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
        { value: 'marketing_mix4', selected: false, disabled: true },
        { value: 'marketing_mix5', selected: false, disabled: true },
        { value: 'pestel', selected: false, disabled: true },
        { value: 'smart', selected: false },
        { value: 'stp', selected: false, disabled: true },
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
    },
    documentsSelectegenerateBusinessModelCanvasd() {
      this.$emit('update:isValid', this.documentsSelected && this.documentsSelected.length > 0)
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
  // width: 328px;
  // height: 242px;
  &.selected {
    border: 1px solid $secondary;
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
</style>

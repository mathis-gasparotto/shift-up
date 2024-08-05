<template>
  <div class="q-gutter-sm q-mt-xs column">
    <q-checkbox
      v-for="(option, index) in options"
      :key="index"
      v-model="option.selected"
      :disable="option.disabled"
      :label="$t('document.name.' + option.value)"
    />
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
        this.documents = this.options
          .filter((option) => (this.isPro ? option.selected : !option.disabled && option.selected))
          .map((option) => option.value)
      },
      deep: true
    },
    documentsSelected() {
      this.$emit('update:isValid', this.documentsSelected && this.documentsSelected.length > 0)
    }
  },
  created() {
    if (this.isPro) {
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
    isPro() {
      return false
    },
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

<style lang="scss" scoped></style>

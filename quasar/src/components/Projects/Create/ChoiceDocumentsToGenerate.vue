<template>
  <div class="q-gutter-sm q-mt-xs column">
    <q-checkbox v-for="(option, index) in options" :key="index" v-model="option.selected" :disable="option.disabled"
      :label="option.label" />
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
        { label: 'Business Model Canvas', value: 'business_model_canvas', selected: false },
        { label: 'Buyer Persona', value: 'buyer_persona', selected: false },
        { label: 'Competitor Analysis', value: 'competitor_analysis', selected: false },
        { label: 'Golden Triangle', value: 'golden_triangle', selected: false },
        { label: '4P', value: 'marketing_mix4', selected: false, disabled: true },
        { label: '5P', value: 'marketing_mix5', selected: false, disabled: true },
        { label: 'PESTEL', value: 'pestel', selected: false, disabled: true },
        { label: 'SMART', value: 'smart', selected: false },
        { label: 'STP', value: 'stp', selected: false, disabled: true },
        { label: 'SWOT', value: 'swot', selected: false }
      ]
    }
  },
  watch: {
    options: {
      handler() {
        this.documents = this.options.filter((option) => this.isPro ? option.selected : !option.disabled && option.selected).map((option) => option.value)
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
    },
  }
}
</script>

<style lang="scss" scoped></style>

<template>
  <div>
    <h1>Choose documents to generate</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <div class="q-gutter-sm q-mt-xs column">
          <q-checkbox v-for="(option, index) in options" :key="index" v-model="option.selected"
            :disable="option.disabled" :label="option.label" />
        </div>
      </div>

      <SUbtn label="Generate" type="submit" color="gradient" class="fixed-bottom-right q-mr-xl q-mb-xl"
        :disabled="!isValid" />

    </q-form>
  </div>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'

export default {
  name: 'Step3',
  emits: ['submit', 'updated:form'],
  props: {
    form: {
      type: Object,
      required: true
    }
  },
  components: {
    SUbtn
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
        this._form.documents = this.options.filter((option) => this.isPro ? option.selected : !option.disabled && option.selected).map((option) => option.value)
      },
      deep: true
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
  },
  computed: {
    isPro() {
      return false
    },
    isValid() {
      return this._form.documents && this._form.documents.length > 0
    },
    _form: {
      set() {
        this.$emit('updated:form', this._form)
      },
      get() {
        return this.form
      }
    }
  },
  methods: {
    onSubmit() {
      this.$emit('submit')
    }
  }
}
</script>

<style lang="scss" scoped></style>

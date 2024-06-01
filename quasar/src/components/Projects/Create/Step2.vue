<template>
  <div>
    <h1>Define your project</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <label class="text-weight-medium label-required">Project type</label>
        <div class="q-gutter-sm q-mt-xs column">
          <q-radio v-model="localForm.type" v-for="(option, index) in options" :key="index" checked-icon="task_alt"
            unchecked-icon="panorama_fish_eye" :val="option.value" :label="option.label" />
        </div>
      </div>

      <SUbtn label="Next step" type="submit" color="gradient" class="fixed-bottom-right q-mr-lg q-mb-lg"
        :disabled="!isValid" />

    </q-form>
  </div>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'

export default {
  name: 'Step2',
  emits: ['submit', 'updated:form'],
  props: {
    form: {
      type: Object,
      required: false
    }
  },
  components: {
    SUbtn
  },
  data() {
    return {
      options: [
        { label: 'Product for sale', value: 'product-for-sale' },
        { label: 'Service/Performance', value: 'service' },
        { label: 'Innovating product/Concept', value: 'concept' }
      ]
    }
  },
  computed: {
    isValid() {
      return this.localForm.type
    },
    localForm: {
      set() {
        this.$emit('updated:form', this.localForm)
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

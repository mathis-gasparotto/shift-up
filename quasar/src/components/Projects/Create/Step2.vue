<template>
  <div>
    <h1>Définisser votre projet</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <label class="text-weight-medium label-required">Type de projet</label>
        <div class="q-gutter-sm q-mt-xs column">
          <q-radio v-model="_form.sellingObject" v-for="(option, index) in options" :key="index" checked-icon="task_alt"
            unchecked-icon="panorama_fish_eye" :val="option.value" :label="option.label" />
        </div>
      </div>

      <SUbtn label="Passer à l’étape suivante" type="submit" color="gradient" class="fixed-bottom-right q-mr-xl q-mb-xl"
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
      required: true
    }
  },
  components: {
    SUbtn
  },
  data() {
    return {
      options: [
        { label: 'Produit à vendre', value: 'product-for-sale' },
        { label: 'Service/Prestation', value: 'service' },
        { label: 'Produit innovant/Concept', value: 'concept' }
      ]
    }
  },
  computed: {
    isValid() {
      return this._form.sellingObject
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

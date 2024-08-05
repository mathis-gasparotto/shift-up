<template>
  <div>
    <h1>{{ $t('project.create.step3') }}</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <ChoiceDocumentsToGenerate
          ref="choiceDocumentsToGenerate"
          v-model:documents-selected="_form.documents"
          @update:isValid="(value) => (isValid = value)"
        />
      </div>

      <SUbtn
        :label="$t('project.create.submit')"
        type="submit"
        color="gradient"
        class="fixed-bottom-right q-mr-xl q-mb-xl"
        :disabled="!isValid"
        :loading="loading"
      />
    </q-form>
  </div>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import ChoiceDocumentsToGenerate from 'src/components/Projects/Create/ChoiceDocumentsToGenerate.vue'

export default {
  name: 'Step3',
  emits: ['submit', 'update:form'],
  props: {
    form: {
      type: Object,
      required: true
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      isValid: false
    }
  },
  components: {
    SUbtn,
    ChoiceDocumentsToGenerate
  },
  computed: {
    _form: {
      set() {
        this.$emit('update:form', this._form)
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

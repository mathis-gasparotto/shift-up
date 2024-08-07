<template>
  <div>
    <h1>{{ $t('project.create.step1') }}</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <SUinput
          v-model="_form.name"
          :label="$t('form.project.label.name')"
          name="name"
          type="text"
          :placeholder="$t('form.project.placeholder.name')"
          required
          :minLength="3"
        />
      </div>
      <div class="w-100">
        <SUinput
          v-model="_form.description"
          :label="$t('form.project.label.description')"
          name="description"
          type="textarea"
          :placeholder="$t('form.project.placeholder.description')"
          required
          :minLength="3"
        />
      </div>

      <SUbtn
        :label="$t('project.create.nextStep')"
        type="submit"
        color="gradient"
        class="fixed-bottom-right q-mr-xl q-mb-xl"
        :disabled="!isValid"
      />
    </q-form>
  </div>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'
import SUinput from 'src/components/SUinput.vue'

export default {
  name: 'Step1',
  emits: ['submit', 'updated:form'],
  props: {
    form: {
      type: Object,
      required: true
    }
  },
  components: {
    SUbtn,
    SUinput
  },
  computed: {
    isValid() {
      return this._form.name && this._form.description
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

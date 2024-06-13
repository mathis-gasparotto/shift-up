<template>
  <div>
    <h1>Créez votre projet</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <label for="name" class="text-weight-medium label-required">Nom du projet</label>
        <q-input v-model="_form.name" outlined for="name" placeholder="Ajoutez un nom à votre projet"
          class="input q-mb-md" type="text" lazy-rules :rules="[
      (val) =>
        val.trim().length > 0 || 'Vous devez entrer un nom de projet'
    ]" />
      </div>
      <div class="w-100">
        <label for="description" class="text-weight-medium label-required">Description</label>
        <q-input v-model="_form.description" outlined for="description" placeholder="Décrivez votre projet au maximum"
          class="input q-mb-md" type="textarea" lazy-rules :rules="[
      (val) =>
        val.trim().length > 0 || 'Vous devez entrer une description de projet'
    ]" />
      </div>

      <SUbtn label="Prochaine étape" type="submit" color="gradient" class="fixed-bottom-right q-mr-xl q-mb-xl"
        :disabled="!isValid" />

    </q-form>
  </div>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'

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
    SUbtn
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

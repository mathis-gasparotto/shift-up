<template>
  <div>
    <h1>Create your project</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <label for="name" class="text-weight-medium label-required">Name</label>
        <q-input v-model="localForm.name" outlined for="name" placeholder="Add a name to your project"
          class="input q-mb-md" type="text" lazy-rules :rules="[
      (val) =>
        val.trim().length > 0 || 'You must enter a project name'
    ]" />
      </div>
      <div class="w-100">
        <label for="description" class="text-weight-medium label-required">Description</label>
        <q-input v-model="localForm.description" outlined for="description"
          placeholder="Describe your project as much as possible" class="input q-mb-md" type="textarea" lazy-rules
          :rules="[
      (val) =>
        val.trim().length > 0 || 'You must enter a project description'
    ]" />
      </div>

      <SUbtn label="Next step" type="submit" color="gradient" class="fixed-bottom-right q-mr-lg q-mb-lg"
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
      required: false
    }
  },
  components: {
    SUbtn
  },
  computed: {
    isValid() {
      return this.localForm.name && this.localForm.description
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

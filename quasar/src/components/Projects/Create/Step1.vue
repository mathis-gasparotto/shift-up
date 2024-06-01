<template>
  <div>
    <h1>Create your project</h1>
    <q-form @submit.prevent="onSubmit">
      <div class="w-100">
        <label for="name" class="text-weight-medium label-required">Name</label>
        <q-input v-model="form.name" outlined for="name" placeholder="Add a name to your project" class="input q-mb-md"
          type="text" lazy-rules :rules="[
      (val) =>
        val.trim().length > 0 || 'You must enter a project name'
    ]" />
      </div>
      <div class="w-100">
        <label for="description" class="text-weight-medium label-required">Description</label>
        <q-input v-model="form.description" outlined for="description"
          placeholder="Describe your project as much as possible" class="input q-mb-md" type="textarea" lazy-rules
          :rules="[
      (val) =>
        val.trim().length > 0 || 'You must enter a project description'
    ]" />
      </div>

      <SUbtn label="Next step" type="submit" color="gradient" class="fixed-bottom-right q-mr-lg q-mb-lg" />

    </q-form>
  </div>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'

export default {
  name: 'Step1',
  emits: ['submit'],
  components: {
    SUbtn
  },
  data() {
    return {
      form: {
        name: '',
        description: ''
      }
    }
  },
  methods: {
    onSubmit() {
      this.$emit('submit', this.form)
    }
  }
}
</script>

<style lang="scss" scoped></style>

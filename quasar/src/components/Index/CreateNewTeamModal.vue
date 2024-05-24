<template>
  <Modal title="Create new team" ref="modal" buttonsAlign="full">
    <q-form class="w-100 gap-10 column items-center q-mb-xl" @submit.prevent="submit">
      <div class="w-100">
        <label for="name" class="text-weight-medium label-required">Name</label>
        <q-input v-model="form.name" outlined for="name" placeholder="Add a name for your team" class="input"
          type="text" lazy-rules :rules="[
      (val) =>
        val && val.trim().length > 3 || 'Team name required'
    ]" />
      </div>
      <p v-if="error" class="text-negative q-mb-none">{{ error }}</p>
    </q-form>
    <template #buttons>
      <SUbtn label="Create" color="gradient" rounded class="w-100" type="submit" :loading="loading" @click="submit" />
    </template>
  </Modal>
</template>

<script>
import Modal from 'src/components/Modal.vue'
import SUbtn from 'src/components/SUbtn.vue'

export default {
  name: 'CreateNewTeamModal',
  components: {
    Modal,
    SUbtn
  },
  data() {
    return {
      form: {
        name: '',
        members: []
      },
      error: '',
      loading: false
    }
  },
  methods: {
    submit() {
      console.log(this.form)
    },
    openModal() {
      this.$refs.modal.open = true
    }
  }
}
</script>

<style lang="scss" scoped>
.card {
  border-radius: 24px;
}
</style>

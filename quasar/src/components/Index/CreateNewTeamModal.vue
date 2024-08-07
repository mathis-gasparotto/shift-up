<template>
  <Modal
    :title="$t('team.createModal.title')"
    ref="modal"
    buttonsAlign="full"
  >
    <q-form
      class="w-100 gap-10 column items-center q-mb-xl"
      @submit.prevent="submit"
    >
      <div class="w-100">
        <SUinput
          v-model="form.name"
          :label="$t('form.team.label.name')"
          name="name"
          type="text"
          :placeholder="$t('form.team.placeholder.name')"
          required
          :minLength="3"
        />
      </div>
      <p
        v-if="error"
        class="text-negative q-mb-none"
      >
        {{ error }}
      </p>
    </q-form>
    <template #buttons>
      <SUbtn
        :label="$t('team.createModal.submit')"
        color="gradient"
        rounded
        class="w-100"
        type="submit"
        :loading="loading"
        @click="submit"
      />
    </template>
  </Modal>
</template>

<script>
import Modal from 'src/components/Modal.vue'
import SUbtn from 'src/components/SUbtn.vue'
import SUinput from 'src/components/SUinput.vue'
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'CreateNewTeamModal',
  components: {
    Modal,
    SUbtn,
    SUinput
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
      successNotify(this.$t('team.createModal.success'))
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

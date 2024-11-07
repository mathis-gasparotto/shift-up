<template>
  <Modal
    v-if="team"
    :title="$t('team.editModal.title', { name: team.name })"
    ref="modal"
    buttonsAlign="full"
  >
    <q-form
      class="w-100 gap-10 column items-center q-mb-lg"
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
        :label="$t('team.editModal.submit')"
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
import { translateError } from 'src/helpers/translatting'
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'TeamSettingsModal',
  emits: ['updated'],
  components: {
    Modal,
    SUbtn,
    SUinput
  },
  props: {
    team: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      form: {},
      error: '',
      loading: false
    }
  },
  created() {
    this.form = { name: this.team.name }
  },
  methods: {
    submit() {
      this.loading = true
      this.$resources.teams
        .update(this.team.id, this.form)
        .then(() => {
          this.$refs.modal.open = false
          successNotify(this.$t('team.editModal.success'))
          this.$emit('updated', { ...this.team, ...this.form })
        })
        .catch((error) => {
          if (error.response.status === 403) {
            this.error = this.$t('team.editModal.error403')
          } else {
            this.error = translateError(error)
          }
        })
        .finally(() => {
          this.loading = false
        })
    },
    openModal() {
      this.$refs.modal.open = true
    }
  }
}
</script>

<style lang="scss" scoped></style>

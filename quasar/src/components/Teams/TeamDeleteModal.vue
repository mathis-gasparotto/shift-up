<template>
  <Modal
    v-if="team"
    :title="$t('team.deleteModal.title', { name: team.name })"
    ref="modal"
    buttonsAlign="full"
  >
    <p
      class="text-bold"
      v-html="$t('team.deleteModal.content', { name: team.name })"
    ></p>
    <p
      v-if="error"
      class="text-negative q-mb-none"
    >
      {{ error }}
    </p>
    <template #buttons>
      <SUbtn
        :label="$t('team.deleteModal.submit')"
        color="negative"
        rounded
        class="w-100"
        type="submit"
        :loading="loading"
        @click="deleteTeam"
      />
    </template>
  </Modal>
</template>

<script>
import Modal from 'src/components/Modal.vue'
import SUbtn from 'src/components/SUbtn.vue'
import { translateError } from 'src/helpers/translatting'
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'TeamDeleteModal',
  emits: ['deleted'],
  components: {
    Modal,
    SUbtn
  },
  props: {
    team: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      error: '',
      loading: false
    }
  },
  methods: {
    openModal() {
      this.$refs.modal.open = true
    },
    deleteTeam() {
      this.loading = true
      this.$resources.teams
        .delete(this.team.id)
        .then(() => {
          this.$emit('deleted')
          this.$emitter.emit('reloadNavbar')
          this.$refs.modal.open = false
          successNotify(this.$t('team.deleteModal.success'))
        })
        .catch((err) => {
          if (err.response && err.response.status === 403) {
            this.error = this.$t('team.deleteModal.error403')
          } else {
            this.error = translateError(err, this.$t('team.deleteModal.error'))
          }
        })
        .finally(() => {
          this.loading = false
        })
    }
  }
}
</script>

<style lang="scss" scoped>
.card {
  border-radius: 24px;
}
</style>

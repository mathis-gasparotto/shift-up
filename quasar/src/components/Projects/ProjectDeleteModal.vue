<template>
  <Modal
    v-if="project"
    :title="$t('project.deleteModal.title')"
    ref="modal"
    buttonsAlign="full"
  >
    <p class="text-bold">
      {{ $t('project.deleteModal.content', { name: project.name }) }}
    </p>
    <p
      v-if="error"
      class="text-negative q-mb-none"
    >
      {{ error }}
    </p>
    <template #buttons>
      <SUbtn
        :label="$t('project.deleteModal.submit')"
        color="negative"
        rounded
        class="w-100"
        type="submit"
        :loading="loading"
        @click="deleteProject"
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
  name: 'ProjectDeleteModal',
  emits: ['deleted'],
  components: {
    Modal,
    SUbtn
  },
  props: {
    project: {
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
    deleteProject() {
      this.loading = true
      this.$resources.projects
        .delete(this.project.id)
        .then(() => {
          this.$emit('deleted')
          this.$emitter.emit('reloadNavbar')
          this.$refs.modal.open = false
          successNotify(this.$t('project.deleteModal.success'))
        })
        .catch((err) => {
          this.error = translateError(err, this.$t('project.deleteModal.error'))
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

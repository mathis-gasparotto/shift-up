<template>
  <Modal
    v-if="project"
    :title="$t('project.editModal.title')"
    :subtitle="project.name"
    ref="modal"
    buttonsAlign="full"
  >
    <q-form
      class="w-100 gap-10 column items-center q-mb-lg"
      @submit.prevent="submit"
    >
      <div class="w-100">
        <label
          for="name"
          class="text-weight-medium label-required"
          >{{ $t('form.project.label.name') }}</label
        >
        <q-input
          v-model="form.name"
          outlined
          for="name"
          :placeholder="$t('form.project.placeholder.name')"
          class="input"
          type="text"
          lazy-rules
          :rules="[(val) => (val && val.trim().length > 3) || $t('form.error.required')]"
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
        :label="$t('project.editModal.submit')"
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
import { translateError } from 'src/helpers/translatting'
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'ProjectSettingsModal',
  emits: ['updated'],
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
      form: {},
      error: '',
      loading: false
    }
  },
  created() {
    this.form = { name: this.project.name }
  },
  methods: {
    submit() {
      this.loading = true
      this.$resources.projects
        .update(this.project.id, this.form)
        .then(() => {
          this.$refs.modal.open = false
          successNotify($t('project.editModal.success'))
          this.$emit('updated')
        })
        .catch((error) => {
          this.error = translateError(error)
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

<style lang="scss" scoped>
.card {
  border-radius: 24px;
}
</style>

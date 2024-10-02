<template>
  <Modal
    v-if="project"
    :title="$t('project.regenerateModal.title')"
    :subtitle="project.name"
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
          v-model="form.description"
          :label="$t('form.project.label.description')"
          name="name"
          type="textarea"
          :placeholder="$t('form.project.placeholder.description')"
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
        :label="$t('project.regenerateModal.submit')"
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
  name: 'ProjectRegenerateModal',
  emits: ['generated'],
  components: {
    Modal,
    SUbtn,
    SUinput
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
    this.form = { name: this.project.name, description: this.project.description }
  },
  methods: {
    submit() {
      this.loading = true
      return console.log(this.form)
      this.$resources.projects
        .update(this.project.id, this.form)
        .then(() => {
          this.$resources.projects
            .simplePost(`${this.project.id}/generate`, this.form)
            .then(() => {
              this.$refs.modal.open = false
              successNotify(this.$t('project.regenerateModal.success'))
              this.$emit('generated')
            })
            .catch((error) => {
              this.error = translateError(error)
              this.loading = false
            })
            .finally(() => {
              this.loading = false
            })
        })
        .catch((error) => {
          this.error = translateError(error)
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

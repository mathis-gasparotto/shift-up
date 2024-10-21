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
      <div class="w-100">
        <SUlabel
          :label="$t('project.regenerateModal.documentsLabel')"
          name="documents"
          required
        />
        <div>
          <ChoiceDocumentsToGenerate v-model:documentsSelected="documents" />
        </div>
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
import SUlabel from 'src/components/SUlabel.vue'
import SUinput from 'src/components/SUinput.vue'
import ChoiceDocumentsToGenerate from 'src/components/Projects/Create/ChoiceDocumentsToGenerate.vue'
import { displayError } from 'src/helpers/translatting'
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'ProjectRegenerateModal',
  emits: ['generated'],
  components: {
    Modal,
    SUbtn,
    SUinput,
    SUlabel,
    ChoiceDocumentsToGenerate
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
      loading: false,
      documents: []
    }
  },
  created() {
    this.form = { name: this.project.name, description: this.project.description }
  },
  methods: {
    submit() {
      this.loading = true
      this.$resources.projects
        .update(this.project.id, this.form)
        .then(() => {
          this.$resources.projects
            .generateDocuments(this.project.id, { documents: this.documents })
            .then(() => {
              this.$refs.modal.open = false
              successNotify(this.$t('project.regenerateModal.success'))
              this.$emit('generated')
            })
            .catch((error) => {
              displayError(error, this.$t('project.regenerateModal.generateDocumentsError'))
              this.loading = false
            })
            .finally(() => {
              this.loading = false
            })
        })
        .catch((error) => {
          displayError(error, this.$t('project.regenerateModal.updateProjectError'))
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

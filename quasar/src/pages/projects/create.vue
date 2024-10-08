<template>
  <q-page>
    <MainBreadcrumps
      :loading="loading"
      :team-name="team.name"
    />
    <div class="nav q-my-xl flex gap-100 items-center">
      <SUbtn
        :label="$t('project.create.back')"
        color="grey-light"
        rounded
        icon="arrow_back"
        textColor="grey"
        @click="goBack"
      />

      <div class="flex items-center gap-30">
        <div class="flex items-center gap-8 steps">
          <q-linear-progress
            :value="step >= 1 ? 1 : 0"
            rounded
            color="primary"
            class="step-bar"
          />
          <q-linear-progress
            :value="step >= 2 ? 1 : 0"
            rounded
            color="primary"
            class="step-bar"
          />
          <q-linear-progress
            :value="step >= 3 ? 1 : 0"
            rounded
            color="primary"
            class="step-bar"
          />
        </div>
        <span class="q-mb-none text-grey">{{ step }}/3</span>
      </div>
    </div>

    <component
      :is="stepComponent"
      @submit="onSubmit"
      v-model:form="form"
      :loading="formLoading"
    />
  </q-page>
</template>

<script>
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import { displayError } from 'src/helpers/translatting'
import SUbtn from 'src/components/SUbtn.vue'
import Step1 from 'src/components/Projects/Create/Step1.vue'
import Step2 from 'src/components/Projects/Create/Step2.vue'
import Step3 from 'src/components/Projects/Create/Step3.vue'
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  components: {
    MainBreadcrumps,
    SUbtn,
    Step1,
    Step2,
    Step3
  },
  data() {
    return {
      team: {},
      step: 1,
      loading: true,
      form: {
        name: '',
        description: '',
        sellingObject: '',
        documents: []
      },
      formLoading: false
    }
  },
  created() {
    this.reloadData()
  },
  computed: {
    stepComponent() {
      return 'Step' + this.step
    }
  },
  methods: {
    onSubmit() {
      if (this.step < 3) {
        return this.step++
      }

      this.formLoading = true
      const payload = {
        team: this.team['@id'] || '/teams/' + this.$route.params.teamId,
        name: this.form.name,
        description: this.form.description,
        sellingObject: this.form.sellingObject
      }
      this.$resources.projects
        .create(payload)
        .then((res) => {
          this.$resources.projects
            .generateDocuments(res.id, { documents: this.form.documents })
            .then(() => {
              successNotify(this.$t('project.create.success'))
            })
            .catch((err) => {
              displayError(err, this.$t('project.create.generateDocumentsError'))
              this.$router.push({ name: 'project', params: { teamId: this.team.id, projectId: res.id } })
            })
            .finally(() => {
              this.formLoading = false
            })
        })
        .catch((err) => {
          displayError(err, this.$t('project.create.createProjectError'))
        })
    },
    reloadData() {
      this.loading = true
      this.$resources.teams
        .get(this.$route.params.teamId)
        .then((res) => {
          this.team = res
          this.loading = false
        })
        .catch((err) => {
          displayError(err)
          this.loading = false
        })
    },
    goBack() {
      if (this.step <= 1) {
        return this.$router.push({ name: 'team', params: { teamId: this.$route.params.teamId } })
      } else {
        return this.step--
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.step-bar {
  width: 110px;
}
</style>

<template>
  <q-page>
    <MainBreadcrumps :loading="loading" :team-name="team.name" />
    <div class="nav q-my-xl flex gap-100 items-center">
      <SUbtn label="Back" color="grey-light" rounded icon="arrow_back" textColor="grey"
        :to="{ name: 'team', params: { teamId: $route.params.teamId } }" />

      <div class="flex items-center gap-30">
        <div class="flex items-center gap-8 steps">
          <q-linear-progress :value="step >= 1 ? 1 : 0" rounded color="primary" class="step-bar" />
          <q-linear-progress :value="step >= 2 ? 1 : 0" rounded color="primary" class="step-bar" />
          <q-linear-progress :value="step >= 3 ? 1 : 0" rounded color="primary" class="step-bar" />
        </div>
        <span class="q-mb-none text-grey">{{ step }}/3</span>
      </div>
    </div>

    <component :is="stepComponent" @submit="onSubmit" />
  </q-page>
</template>

<script>
import MainBreadcrumps from 'src/components/MainBreadcrumps.vue'
import { displayError } from 'src/helpers/translatting'
import SUbtn from 'src/components/SUbtn.vue'
import Step1 from 'src/components/Projects/Create/Step1.vue'

export default {
  components: {
    MainBreadcrumps,
    SUbtn,
    Step1
  },
  data() {
    return {
      team: {},
      step: 1,
      loading: true,
      stepComponent: 'Step1',
      forms: {}
    }
  },
  created() {
    this.reloadData()
  },
  methods: {
    onSubmit(data) {
      this.forms = { ...this.forms, ...data }
      console.log(this.forms)
    },
    reloadData() {
      this.loading = true
      this.$resources.teams.get(this.$route.params.teamId).then((res) => {
        this.team = res
        this.loading = false
      }).catch((err) => {
        displayError(err)
        this.loading = false
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.step-bar {
  width: 110px;
}
</style>

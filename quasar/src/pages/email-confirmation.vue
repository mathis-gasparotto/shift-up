<template>
  <q-layout
    view="lHh Lpr lFf"
    class="relative bg-main"
  >
    <q-page-container>
      <q-page class="flex flex-center">
        <q-spinner
          color="primary"
          size="3em"
          v-if="loading"
        />
        <InfoCard
          v-else-if="!error"
          type="success"
          :title="$t('emailConfirmation.successTitle')"
          :action-btn-label="$t('emailConfirmation.action')"
          :action-btn-route="{ name: 'signin' }"
        />
        <InfoCard
          v-else
          type="error"
          :title="error"
          :action-btn-label="$t('emailConfirmation.action')"
          :action-btn-route="{ name: 'signin' }"
        />
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import InfoCard from 'src/components/InfoCard.vue'
import { translateError } from 'src/helpers/translatting'

export default {
  components: {
    InfoCard
  },
  data() {
    return {
      loading: true,
      error: ''
    }
  },
  created() {
    this.$auth
      .confirmEmail(this.$route.params.token)
      .then(() => {
        this.loading = false
      })
      .catch((error) => {
        this.error = translateError(error)
        this.loading = false
      })
  }
}
</script>

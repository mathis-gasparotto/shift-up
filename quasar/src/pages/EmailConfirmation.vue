<template>
  <q-layout view="lHh Lpr lFf" class="relative bg-main">
    <q-page-container>
      <q-page class="flex flex-center">
        <q-spinner color="primary" size="3em" v-if="loading" />
        <InfoCard v-else-if="!error" type="success" title="Your email address has been confirmed!"
          action-btn-label="Go for sign in" :action-btn-route="{ name: 'signin' }">
        </InfoCard>
        <InfoCard v-else type="error" :title="error" action-btn-label="Back to sign in page"
          :action-btn-route="{ name: 'signin' }">
        </InfoCard>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import InfoCard from 'src/components/InfoCard.vue'
import { translateError } from 'src/helpers/translatting'

export default {
  name: 'EmailConfirmation',
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
    this.$auth.confirmEmail(this.$route.params.token).then(() => {
      this.loading = false
    }).catch((error) => {
      this.error = translateError(error)
      this.loading = false
    })
  }
}
</script>

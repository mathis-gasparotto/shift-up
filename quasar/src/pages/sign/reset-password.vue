<template>
  <q-page class="flex-center column page q-mx-auto q-py-xl">
    <div class="w-100 q-mb-lg">
      <h2 class="text-h4 q-mb-sm q-mt-none">{{ $t('resetPassword.title') }}</h2>
      <p>
        {{ $t('resetPassword.content') }}
      </p>
    </div>
    <ResetPasswordForm />
    <p class="q-mt-lg">
      <router-link
        :to="{ name: 'signin' }"
        class="text-bold"
        >{{ $t('resetPassword.back') }}</router-link
      >
    </p>
  </q-page>
</template>

<script>
import ResetPasswordForm from 'src/components/ResetPasswordForm.vue'

export default {
  components: {
    ResetPasswordForm
  }
}
</script>

<style lang="scss" scoped>
.page {
  max-width: 410px;
}
</style>

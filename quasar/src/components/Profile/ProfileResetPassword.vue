<template>
  <div class="w-100 q-mb-lg">
    <h2 class="text-h5 q-mb-sm q-mt-none">{{ $t('profile.resetPassword.title') }}</h2>
    <p>
      {{ $t('profile.resetPassword.content') }}
    </p>
  </div>
  <ResetPasswordForm />
</template>

<script>
import ResetPasswordForm from 'src/components/ResetPasswordForm.vue'

export default {
  name: 'ProfileResetPassword',
  components: {
    ResetPasswordForm
  }
}
</script>

<style lang="scss" scoped></style>

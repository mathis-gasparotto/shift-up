<template>
  <div class="w-100 q-mb-lg">
    <h2 class="text-h5 q-mb-sm q-mt-none">{{ $t('profile.deleteAccount.title') }}</h2>
    <p>
      {{ $t('profile.deleteAccount.content') }}
    </p>
  </div>
  <DeleteAccountForm />
</template>

<script>
import DeleteAccountForm from 'src/components/DeleteAccountForm.vue'

export default {
  name: 'ProfileDeleteAccount',
  components: {
    DeleteAccountForm
  }
}
</script>

<style lang="scss" scoped></style>

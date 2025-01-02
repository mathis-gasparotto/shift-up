<template>
  <q-page class="page q-mx-auto q-py-xl">
    <h1 class="q-mb-xl q-mt-none">{{ $t('profile.title') }}</h1>
    <div class="items-center column page-wrapper">
      <div class="page-content">
        <ProfileResetPassword />
      </div>
    </div>
  </q-page>
</template>

<script>
import ProfileResetPassword from 'src/components/Profile/ProfileResetPassword.vue'

export default {
  components: {
    ProfileResetPassword
  }
}
</script>

<style lang="scss" scoped>
.page-content {
  max-width: 410px;
}
</style>

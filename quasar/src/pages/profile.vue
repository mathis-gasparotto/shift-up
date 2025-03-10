<template>
  <q-page class="page q-mx-auto q-py-xl">
    <h1 class="q-mb-xl q-mt-none">{{ $t('profile.title') }}</h1>
    <div class="items-center column page-wrapper">
      <div class="page-content">
        <div class="column gap-100">
          <div>
            <ProfileResetPassword />
          </div>
          <div>
            <ProfileDeleteAccount />
          </div>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script>
import ProfileResetPassword from 'src/components/Profile/ProfileResetPassword.vue'
import ProfileDeleteAccount from 'src/components/Profile/ProfileDeleteAccount.vue'

export default {
  components: {
    ProfileResetPassword,
    ProfileDeleteAccount
  }
}
</script>

<style lang="scss" scoped>
.page-content {
  max-width: 410px;
}
</style>

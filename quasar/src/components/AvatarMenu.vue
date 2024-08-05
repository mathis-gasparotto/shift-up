<template>
  <q-menu auto-clos>
    <q-list style="min-width: 100px">
      <q-item clickable>
        <q-item-section>{{ $t('nav.profile') }}</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>{{ $t('nav.settings') }}</q-item-section>
      </q-item>
      <q-separator />
      <q-item
        clickable
        @click="logout"
      >
        <q-item-section>{{ $t('nav.logout') }}</q-item-section>
      </q-item>
    </q-list>
  </q-menu>
</template>

<script>
import { successNotify } from 'src/helpers/notifyHelper'

export default {
  name: 'AvatarMenu',
  methods: {
    logout() {
      this.$auth.logout()
      this.$router.push({ name: 'signin' })
      successNotify($t('nav.logoutSuccess'))
    }
  }
}
</script>

<style lang="scss" scoped></style>

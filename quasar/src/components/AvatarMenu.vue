<template>
  <q-menu auto-clos>
    <q-list style="min-width: 100px">
      <q-item clickable>
        <q-item-section>New tab</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>New incognito tab</q-item-section>
      </q-item>
      <q-separator />
      <q-item clickable>
        <q-item-section>Recent tabs</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>History</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>Downloads</q-item-section>
      </q-item>
      <q-separator />
      <q-item clickable>
        <q-item-section>Settings</q-item-section>
      </q-item>
      <q-separator />
      <q-item clickable>
        <q-item-section>Help &amp; Feedback</q-item-section>
      </q-item>
      <q-item clickable @click="logout">
        <q-item-section>Logout</q-item-section>
      </q-item>
    </q-list>
  </q-menu>
</template>

<script>
import { successNotify } from 'src/helpers/notifyHelper';


export default {
  name: 'AvatarMenu',
  methods: {
    logout() {
      this.$auth.logout()
      this.$router.push({ name: 'signin' })
      successNotify('You have successfully signed out!')
    }
  }
}
</script>

<style lang="scss" scoped></style>

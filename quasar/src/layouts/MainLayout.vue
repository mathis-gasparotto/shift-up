<template>
  <q-layout view="lHh Lpr lFf" class="relative bg-main">
    <q-btn class="fixed-top-left q-ma-md navbar-btn" clickable flat dense icon="menu" aria-label="Menu"
      @click="$refs.navbarDesktop.toggleNav()" />

    <NavbarDesktop ref="navbarDesktop" />

    <q-avatar :class="'fixed-top-right q-ma-md cursor-pointer avatar avatar-' + avatarColor" v-if="firstLetter"
      text-color="white" size="lg">
      {{ firstLetter }}
      <AvatarMenu />
    </q-avatar>

    <q-page-container>
      <router-view class="q-pa-xl" />
    </q-page-container>
  </q-layout>
</template>

<script>
import NavbarDesktop from 'components/NavbarDesktop.vue'
import AvatarMenu from 'components/AvatarMenu.vue'
import { getAvatarColor } from 'src/helpers/avatarHelper'

export default {
  name: 'MainLayout',
  components: {
    NavbarDesktop,
    AvatarMenu
  },
  computed: {
    firstLetter() {
      return this.$auth.getUser ? this.$auth.getUser.firstName.charAt(0) : null
    },
    avatarColor() {
      return getAvatarColor(this.firstLetter)
    }
  }
}
</script>
<style lang="scss" scoped>
.avatar,
.navbar-btn {
  z-index: 1000;
}
</style>

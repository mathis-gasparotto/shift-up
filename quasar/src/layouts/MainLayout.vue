<template>
  <q-layout view="lHh Lpr lFf" class="relative bg-main">
    <q-btn class="fixed-top-left q-ma-md navbar-btn" clickable flat dense icon="menu" aria-label="Menu"
      @click="$refs.navbarDesktop.toggleNav()" />

    <NavbarDesktop ref="navbarDesktop" />

    <q-avatar class="fixed-top-right q-ma-md cursor-pointer avatar">
      <img src="https://cdn.quasar.dev/img/avatar.png">
      <AvatarMenu />
    </q-avatar>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import NavbarDesktop from 'components/NavbarDesktop.vue'
import AvatarMenu from 'components/AvatarMenu.vue'

export default {
  name: 'MainLayout',
  components: {
    NavbarDesktop,
    AvatarMenu
  }
}
</script>
<style lang="scss" scoped>
.avatar,
.navbar-btn {
  z-index: 1000;
}
</style>

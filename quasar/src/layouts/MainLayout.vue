<template>
  <q-layout view="lHh Lpr lFf" class="relative">
    <q-btn v-if="showNavBtn" class="fixed-top-left z-top q-ma-md" clickable flat dense icon="menu" aria-label="Menu"
      @click="() => {
      showNavBtn = false
      $refs.navbarDesktop.toggleNav()
    }" />


    <NavbarDesktop ref="navbarDesktop" @hideNav="showNavBtn = true" />

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import NavbarDesktop from 'components/NavbarDesktop.vue'

export default {
  name: 'MainLayout',
  components: {
    NavbarDesktop
  },
  data () {
    return {
      showNavBtn: false
    }
  }
}
</script>

<template>
  <q-layout
    view="lHh Lpr lFf"
    class="relative bg-main"
  >
    <q-btn
      class="fixed-top-left q-ma-md navbar-btn"
      clickable
      flat
      dense
      icon="menu"
      aria-label="Menu"
      @click="$refs.navbarDesktop.toggleNav()"
    />

    <NavbarDesktop ref="navbarDesktop" />

    <q-avatar
      :class="'absolute-top-right q-ma-lg q-ma-xs-xl cursor-pointer avatar avatar-' + avatarColor"
      size="lg"
    >
      <q-skeleton
        v-if="$auth.isLoading"
        type="rect"
        size="20px"
      />
      <template v-else>
        {{ firstLetter }}
      </template>
      <AvatarMenu />
    </q-avatar>

    <q-page-container>
      <router-view class="q-pa-xl q-ma-xs-sm" />
    </q-page-container>
  </q-layout>
</template>

<script>
import NavbarDesktop from 'src/components/NavbarDesktop.vue'
import AvatarMenu from 'src/components/AvatarMenu.vue'
import { getAvatarColor } from 'src/helpers/avatarHelper'

export default {
  name: 'MainLayout',
  components: {
    NavbarDesktop,
    AvatarMenu
  },
  created() {
    this.$emitter.on('reloadNavbar', (evt) => {
      this.$refs.navbarDesktop.reloadData()
    })
  },
  computed: {
    firstLetter() {
      return this.$auth.getUser ? this.$auth.getUser.firstName.charAt(0) : null
    },
    avatarColor() {
      const colorNumber = getAvatarColor(this.firstLetter)
      return colorNumber ?? 'default'
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

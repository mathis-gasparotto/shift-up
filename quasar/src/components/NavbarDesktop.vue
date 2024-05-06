<template>
  <q-drawer v-model="open" show-if-above bordered>
    <q-tabs vertical class="nav-bar nav-bar--desktop q-pa-xl q-mx-auto" align="justify" indicator-color="transparent"
      active-color="primary">
      <q-item-label header class="h-15">
        <a href="/" @click.prevent="$router.push({ name: 'index' })">
          <q-img src="~assets/shift-up-logo.png" class="logo"></q-img>
        </a>
      </q-item-label>

      <q-list class=" h-70">
        <q-item v-for="(navItem, index) in navItems" :key="index" :to="navItem.route" :tabindex="index">
          <q-item-section side>
            <q-icon :name="navItem.icon" :color="isCurrentRoute(navItem.route) ? 'primary' : 'grey'" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="nav-title">{{ navItem.title }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>

      <div class="h-15">
        <SUbtn label="Upgrade Plan" rounded color="gradient" class="w-100 q-mb-md" />
        <q-btn label="Réduire" no-caps flat icon="sym_o_keyboard_tab_rtl" color="grey" class="w-100" align="left"
          rounded @click="open = !open" />
      </div>
    </q-tabs>

  </q-drawer>
</template>

<script>
import SUbtn from 'src/components/SUbtn.vue'

export default {
  name: 'NavbarDesktop',
  components: {
    SUbtn
  },
  data() {
    return {
      open: true,
      navItems: [
        {
          title: 'Dashboard',
          icon: 'edit',
          route: { name: 'index' }
        },
        {
          title: 'Docs',
          icon: 'edit',
          route: { name: 'test' }
        }
      ]
    }
  },
  methods: {
    isCurrentRoute(route) {
      return route.name === this.$route.name
    }
  }
}
</script>

<style lang="scss" scoped>
.logo {
  width: 128px;
}
.nav-title {
  font-size: 20px;
}
</style>

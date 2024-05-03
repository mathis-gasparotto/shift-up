<template>
  <q-drawer v-model="open" show-if-above bordered>
    <q-tabs vertical class="nav-bar nav-bar--desktop q-px-xl q-mx-auto" indicator-color="transparent"
      active-color="primary">
      <q-item-label header>
        Essential Links
      </q-item-label>

      <SUbtn label="test" rounded color="grey-light" />

      <q-item v-for="(navItem, index) in navItems" :key="index" :to="navItem.route" :tabindex="index">
        <q-item-section side>
          <q-icon :name="navItem.icon" :color="isCurrentRoute(navItem.route) ? 'primary' : 'grey'" />
        </q-item-section>
        <q-item-section>
          <q-item-label>{{ navItem.title }}</q-item-label>
        </q-item-section>
      </q-item>
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
          title: 'Docs',
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

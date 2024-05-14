<template>
  <router-view />
</template>

<script>
export default {
  name: 'App',
  created() {
    if (this.$auth.isAuthenticated) {
      this.$auth.loadUserData(false)
    }
  }
}
</script>

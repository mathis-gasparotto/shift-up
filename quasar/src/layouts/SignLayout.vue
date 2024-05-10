<template>
  <q-layout view="lHh Lpr lFf" class="relative bg-main">
    <div class="row">
      <q-page-container class="w-60 q-px-lg">
        <router-view />
      </q-page-container>
      <img alt="Quasar logo" class="w-40 q-pa-lg" src="~assets/quasar-logo-vertical.svg">
    </div>
  </q-layout>
</template>

<script>
export default {
  name: 'SignLayout'
}
</script>

<template>
  <q-layout
    view="lHh Lpr lFf"
    class="relative bg-main"
  >
    <div class="absolute-top-right q-mt-lg q-mr-lg">
      <LangSelect />
    </div>
    <div class="row-sm column reverse">
      <q-page-container class="w-100 w-sm-60 q-px-sm-lg q-mx-auto">
        <router-view />
      </q-page-container>
      <img
        alt="Quasar logo"
        class="w-60 w-sm-40 q-pa-lg q-mx-auto"
        src="~assets/quasar-logo-vertical.svg"
      />
    </div>
  </q-layout>
</template>

<script>
import LangSelect from 'src/components/LangSelect.vue'

export default {
  name: 'SignLayout',
  components: {
    LangSelect
  }
}
</script>

<template>
  <q-layout
    view="lHh Lpr lFf"
    class="relative bg-main"
  >
    <div class="absolute-top-right q-mt-lg q-mr-lg">
      <LangSelect />
    </div>
    <div class="row-sm column reverse w-80 q-mx-auto">
      <q-page-container class="w-100 w-sm-70 q-px-sm-lg">
        <router-view />
      </q-page-container>
      <img
        alt="Quasar logo"
        class="max-w-60 q-pt-xl q-mt-xl q-pb-lg q-pa-sm-none q-mt-sm-none max-w-sm-30 q-mx-auto"
        src="~assets/shift-up-vertical.svg"
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

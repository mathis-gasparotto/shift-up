<template>
  <q-select
    v-model="lang"
    :options="langOptions"
    borderless
    map-options
    emit-value
    dense
  />
</template>

<script>
import { getCurrentLang, langOptions, updateLang } from 'src/helpers/langs'
import { ref } from 'vue'

export default {
  name: 'LangSelect',
  setup() {
    const lang = ref(getCurrentLang())

    return { lang, langOptions }
  },
  name: 'AvatarMenu',
  watch: {
    lang(val) {
      updateLang(val)
    }
  }
}
</script>

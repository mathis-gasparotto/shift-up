<template>
  <q-menu auto-clos>
    <q-list style="min-width: 150px">
      <q-item clickable>
        <q-item-section>{{ $t('nav.profile') }}</q-item-section>
      </q-item>
      <q-item clickable>
        <q-item-section>{{ $t('nav.settings') }}</q-item-section>
      </q-item>
      <q-separator />
      <q-item clickable>
        <q-item-section>
          <q-select
            v-model="lang"
            :options="langOptions"
            borderless
            map-options
            emit-value
            dense
          />
        </q-item-section>
      </q-item>
      <q-separator />
      <q-item
        clickable
        @click="logout"
      >
        <q-item-section>{{ $t('nav.logout') }}</q-item-section>
      </q-item>
    </q-list>
  </q-menu>
</template>

<script>
import { successNotify } from 'src/helpers/notifyHelper'
import { getCurrentLang, langOptions, updateLang } from 'src/helpers/langs'
import { LocalStorage } from 'quasar'
import { ref } from 'vue'

export default {
  setup() {
    const lang = ref(getCurrentLang())

    return { lang, langOptions }
  },
  name: 'AvatarMenu',
  watch: {
    lang(val) {
      updateLang(val)
    }
  },
  methods: {
    logout() {
      this.$auth.logout()
      this.$router.push({ name: 'signin' })
      successNotify(this.$t('nav.logoutSuccess'))
    }
  }
}
</script>

<style lang="scss" scoped></style>

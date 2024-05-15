import { defineStore } from 'pinia'
import { LocalStorage } from 'quasar'
import { defaultLang } from 'src/helpers/langs'

export const useLangStore = defineStore('lang', {
  state: () => ({
    currentLang: LocalStorage.getItem('lang') || defaultLang
  }),
  getters: {
    getCurrentLang: (state) => state.currentLang
  },
  actions: {
    setLang(lang) {
      LocalStorage.set('lang', lang)
    },
    resetLang() {
      this.setLang(defaultLang)
    }
  }
})

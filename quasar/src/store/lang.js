import { defineStore } from 'pinia'
import { LocalStorage } from 'quasar'
import { langs, defaultLang, langArray, langCodes } from 'src/helpers/langs'

export const useLangStore = defineStore('lang', {
  state: () => ({
    currentLang: LocalStorage.getItem('lang') || defaultLang
  }),
  getters: {
    getCurrentLang: (state) => langs[state.currentLang]
  },
  actions: {
    setLang(lang) {
      LocalStorage.set('lang', lang)
    },
    resetLang() {
      this.setLang(defaultLang)
    },
    getLangsAsArray() {
      return langArray
    },
    getLangCodes() {
      return langCodes
    },
    getLangs() {
      return langs
    }
  }
})

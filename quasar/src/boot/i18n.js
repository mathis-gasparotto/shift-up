import { createI18n } from 'vue-i18n'
import messages from 'src/i18n'
import { boot } from 'quasar/wrappers'
import { Lang, LocalStorage } from 'quasar'
import { defaultLang, getCurrentLang, langsData } from '../helpers/langs'

if (!LocalStorage.getItem('lang')) {
  // Lang.set(Lang.getLocale() ?? defaultLang)
  const localLang = Lang.getLocale()
  LocalStorage.set('lang', localLang && langsData[localLang] ? localLang : defaultLang)
}

const currentLang = getCurrentLang()

// LocalStorage.set('lang', 'fr')

// Create I18n instance
export const i18n = createI18n({
  locale: currentLang,
  legacy: false, // comment this out if not using Composition API
  messages
})

export default boot(async ({ app }) => {
  const quasarLangPack = await import(`../../node_modules/quasar/lang/${currentLang}.mjs`)
  Lang.set(quasarLangPack)

  // Tell app to use the I18n instance
  app.use(i18n)
})

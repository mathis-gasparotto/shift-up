import { createI18n } from 'vue-i18n'
import messages from 'src/i18n'
import { boot } from 'quasar/wrappers'
import { Lang, LocalStorage } from 'quasar'
import { defaultLang, getCurrentLang, langsData } from 'src/helpers/langs'

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
  fallbackLocale: defaultLang,
  // legacy: false, // comment this out if not using Composition API
  // allowComposition: true, // permet l'utilisation de l'API de composition
  // globalInjection: true, // injecte $t, $d, etc. globalement
  messages
})

export default boot(({ app }) => {
  // import(`quasar/lang/${currentLang}`)
  //   .then((quasarLangPack) => {
  //     Lang.set(quasarLangPack)
  //   })
  //   .catch((err) => {
  //     console.error('Error loading quasar lang pack:', err)
  //   })

  // Tell app to use the I18n instance
  app.use(i18n)
})

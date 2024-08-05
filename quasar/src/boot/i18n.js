import { createI18n } from 'vue-i18n'
import messages from 'src/i18n'
import { boot } from 'quasar/wrappers'
import { Lang } from 'quasar'

// Lang.set(Lang.getLocale())
Lang.set('fr-FR')

// Create I18n instance
export const i18n = createI18n({
  locale: Lang.props.isoName || 'en-US',
  legacy: false, // comment this out if not using Composition API
  messages
})

export default boot(({ app }) => {
  // Tell app to use the I18n instance
  app.use(i18n)
})

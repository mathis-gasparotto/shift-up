import moment from 'moment'
import { i18n } from 'boot/i18n'
import { LocalStorage, Lang } from 'quasar'

export const langsData = {
  'en-US': {
    code: 'en-US',
    name: 'English',
    countryCode: 'US',
    momentCode: 'en'
  },
  fr: {
    code: 'fr-FR',
    name: 'Français',
    countryCode: 'FR',
    momentCode: 'fr'
  }
}

export const langOptions = Object.keys(langsData).map((langCode) => {
  const lang = langsData[langCode]
  return {
    value: langCode,
    label: lang.name
  }
})

export const defaultLang = 'en-US'
export const langCodes = Object.keys(langsData)
export const langArray = Object.values(langsData)

export function getCurrentLang() {
  return LocalStorage.getItem('lang') || defaultLang
}

export async function updateLang(isoName) {
  if (typeof isoName !== 'string') {
    return
  }

  const quasarLangPack = await import(`../../node_modules/quasar/lang/${isoName}.mjs`)

  // if (langsData[isoName]) {
  //   moment.locale(langsData[isoName].momentCode)
  // }
  // i18n.global.locale.value = isoName
  LocalStorage.set('lang', isoName)
  // update quasar html lang attribute
  Lang.set(quasarLangPack)

  // reload all app
  window.location.reload()
}

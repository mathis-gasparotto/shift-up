import { errorNotify } from './notifyHelper'
import { useLangStore } from 'src/store/lang'

const langStore = useLangStore()
const currentLang = langStore.getCurrentLang.code

export function translateError(error, defaultMessage = null) {
  let errorMessage
  if (typeof error === 'string') {
    errorMessage = error
  } else {
    errorMessage = error.response
      ? error.response.data['hydra:description'] || error.response.data.detail || error.response.data.message
      : ''
  }

  switch (errorMessage) {
    case 'Incorrect password':
      switch (currentLang) {
        case 'fr-FR':
          return 'Mot de passe incorrect'
        case 'en-US':
          return 'Incorrect password'
        default:
          return 'Incorrect password'
      }
    case 'Invalid credentials':
      switch (currentLang) {
        case 'fr-FR':
          return 'Identifiants incorrects'
        case 'en-US':
          return 'Invalid credentials'
        default:
          return 'Invalid credentials'
      }
    case 'Incorrect current password':
      switch (currentLang) {
        case 'fr-FR':
          return 'Mot de passe actuel incorrect'
        case 'en-US':
          return 'Incorrect current password'
        default:
          return 'Incorrect current password'
      }
    case 'The token has expired':
      switch (currentLang) {
        case 'fr-FR':
          return 'Le lien a expiré'
        case 'en-US':
          return 'The link has expired'
        default:
          return 'The link has expired'
      }
    case 'Invalid token':
      switch (currentLang) {
        case 'fr-FR':
          return 'Le lien est invalide'
        case 'en-US':
          return 'Invalid link'
        default:
          return 'Invalid link'
      }
    case 'Invalid confirmation token':
      switch (currentLang) {
        case 'fr-FR':
          return 'Le lien est invalide'
        case 'en-US':
          return 'Invalid link'
        default:
          return 'Invalid link'
      }
    default:
      if (/.*This value should be greater than.*/gm.test(errorMessage)) {
        switch (currentLang) {
          case 'fr-FR':
            return 'Veuillez choisir une date future'
          case 'en-US':
            return 'Please choose a future date'
          default:
            return 'Please choose a future date'
        }
      }
      if (/.*This value should be less than.*/gm.test(errorMessage)) {
        switch (currentLang) {
          case 'fr-FR':
            return 'Veuillez choisir une date passée'
          case 'en-US':
            return 'Please choose a past date'
          default:
            return 'Please choose a past date'
        }
      }
      if (/^confirmPassword: The password must be confirmed.*/gm.test(errorMessage)) {
        switch (currentLang) {
          case 'fr-FR':
            return 'Les mots de passe ne correspondent pas'
          case 'en-US':
            return 'Passwords do not match'
          default:
            return 'Passwords do not match'
        }
      }
      if (defaultMessage) {
        return defaultMessage
      }
      switch (currentLang) {
        case 'fr-FR':
          return 'Une erreur est survenue'
        case 'en-US':
          return 'Something went wrong'
        default:
          return 'Something went wrong'
      }
  }
}

export function displayError(error, defaultMessage = null) {
  const errorTranslated = translateError(error, defaultMessage)
  errorNotify(errorTranslated)
}

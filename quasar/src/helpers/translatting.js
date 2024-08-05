import { errorNotify } from './notifyHelper'
import { i18n } from 'boot/i18n'
const $t = i18n.global.t

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
      return $t('error.incorrectPassword')
    case 'Invalid credentials':
      return $t('error.invalidCredentials')
    case 'Incorrect current password':
      return $t('error.incorrectCurrentPassword')
    case 'The token has expired':
      return $t('error.theLinkHasExpired')
    case 'Invalid token':
      return $t('error.invalidLink')
    case 'Invalid confirmation token':
      return $t('error.invalidLink')
    case 'phone: This value is already used.':
      return $t('error.phoneNumberAlreadyUsed')
    default:
      if (/.*This value should be greater than.*/gm.test(errorMessage)) {
        return $t('error.pleaseChooseAFutureDate')
      }
      if (/.*This value should be less than.*/gm.test(errorMessage)) {
        return $t('error.pleaseChooseAPastDate')
      }
      if (/^The password must be confirmed.*/gm.test(errorMessage) || /^The new password must be confirmed*/gm.test(errorMessage)) {
        return $t('error.passwordsDoNotMatch')
      }
      if (/^Password must contain at least one lowercase letter, one uppercase letter, one number and one special character*/gm.test(errorMessage)) {
        return $t('error.passwordComplexity')
      }
      if (defaultMessage) {
        return defaultMessage
      }
      console.error('Error message not translated:', error)
      return $t('error.somethingWentWrong')
  }
}

export function displayError(error, defaultMessage = null) {
  if (error.response && error.response.status === 401) return
  const errorTranslated = translateError(error, defaultMessage)
  errorNotify(errorTranslated)
}

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
      return $t('errors.incorrectPassword')
    case 'Invalid credentials':
      return $t('errors.invalidCredentials')
    case 'Incorrect current password':
      return $t('errors.incorrectCurrentPassword')
    case 'The token has expired':
      return $t('errors.theLinkHasExpired')
    case 'Invalid token':
      return $t('errors.invalidLink')
    case 'Invalid confirmation token':
      return $t('errors.invalidLink')
    case 'phone: This value is already used.':
      return $t('errors.phoneNumberAlreadyUsed')
    default:
      if (/.*This value should be greater than.*/gm.test(errorMessage)) {
        return $t('errors.pleaseChooseAFutureDate')
      }
      if (/.*This value should be less than.*/gm.test(errorMessage)) {
        return $t('errors.pleaseChooseAPastDate')
      }
      if (/^confirmPassword: The password must be confirmed.*/gm.test(errorMessage)) {
        return $t('errors.passwordsDoNotMatch')
      }
      if (defaultMessage) {
        return defaultMessage
      }
      console.error('Error message not translated:', error)
      return $t('errors.somethingWentWrong')
  }
}

export function displayError(error, defaultMessage = null) {
  if (error.response && error.response.status === 401) return
  const errorTranslated = translateError(error, defaultMessage)
  errorNotify(errorTranslated)
}

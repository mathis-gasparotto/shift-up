import { errorNotify } from './notifyHelper'
import { i18n } from 'boot/i18n'
const $t = i18n.global.t

export function translateError(error, defaultMessage = null) {
  let errorMessage
  if (typeof error === 'string') {
    errorMessage = error
  } else {
    errorMessage = error.response ? error.response.data['hydra:description'] || error.response.data.detail || error.response.data.message : ''
  }

  switch (errorMessage) {
    case 'Incorrect password':
      return $t('error.incorrectPassword')
    case 'Not Found':
      return $t('error.notFound')
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
    case 'phone: This value is not a valid phone number.':
      return $t('error.phoneNumberInvalid')
    case 'email: This value is already used.':
      return $t('error.emailAlreadyUsed')
    case 'email: This value is not a valid email address.':
      return $t('error.emailInvalid')
    case 'This team cannot be deleted':
      return $t('error.teamCannotBeDeleted')
    case 'You have to choose a subscription plan to create a team':
      return $t('error.chooseSubcriptionForTeam')
    case 'Invalid recurrence, valid recurrences are: MONTH, YEAR':
      return $t('error.incorrectReccurence')
    case 'You already chosen this subscription':
      return $t('error.alreadyChosenSubscription')
    case 'This team has already a subscription change scheduled':
      return $t('error.aleardyPlansChangeSchedule')
    case 'Document not found':
      return $t('error.documentNotFound')
    case 'This subscription change is already scheduled':
      return $t('error.subscriptionChangeAlreadyScheduled')
    case 'Your team avantages are not enough to do this action':
      return $t('error.teamNotEnoughAvantages')
    case 'You need to be a premium team to generate these documents':
      return $t('error.teamNotPremiumForDocuments')
    case 'You need to be a premium team to create a new project':
      return $t('error.teamNotPremiumForProject')
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

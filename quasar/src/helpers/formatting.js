import moment from 'moment/min/moment-with-locales.min'
import { Lang } from 'quasar'
import { getCurrentLang, langsData } from './langs'

// const lang = Lang.props.isoName
const lang = getCurrentLang()
moment.locale(langsData[lang].momentCode)

export function strMaxLenght(str, max) {
  return str.length > max ? str.slice(0, max) + '...' : str
}
export function dateTimeToDisplay(dateTime) {
  if (typeof dateTime === 'string') dateTime = new Date(dateTime)
  return new Date(dateTime).toLocaleString(lang)
}
export function dateToDisplay(date) {
  if (typeof date === 'string') date = new Date(date)
  return new Date(date).toLocaleDateString(lang)
}
export function durationFromDateTime(dateTime) {
  if (typeof dateTime === 'string') dateTime = new Date(dateTime)
  return moment(dateTime).fromNow()
}
export function snakeCaseToCamelCase(str) {
  return str.replace(/_([a-z])/g, (match, letter) => letter.toUpperCase())
}

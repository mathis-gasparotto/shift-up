import { useLangStore } from 'src/store/lang'
import moment from 'moment/min/moment-with-locales.min'

const lang = useLangStore()
moment.locale(lang.getCurrentLang.momentCode)

export function strMaxLenght(str, max) {
  return str.length > max ? str.slice(0, max) + '...' : str
}
export function dateTimeToDisplay(dateTime) {
  if (typeof dateTime === 'string') dateTime = new Date(dateTime)
  if (lang.getCurrentLang) return new Date(dateTime).toLocaleString(lang.getCurrentLang.code)
  return new Date(dateTime).toLocaleString()
}
export function durationFromDateTime(dateTime) {
  if (typeof dateTime === 'string') dateTime = new Date(dateTime)
  return moment(dateTime).fromNow()
}

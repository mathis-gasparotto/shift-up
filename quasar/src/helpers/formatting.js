import moment from 'moment/min/moment-with-locales.min'
import { Lang } from 'quasar'
import { langsData } from './langs'

const lang = Lang.props.isoName
moment.locale(langsData[lang].momentCode)

export function strMaxLenght(str, max) {
  return str.length > max ? str.slice(0, max) + '...' : str
}
export function dateTimeToDisplay(dateTime) {
  if (typeof dateTime === 'string') dateTime = new Date(dateTime)
  return new Date(dateTime).toLocaleString(lang)
}
export function durationFromDateTime(dateTime) {
  if (typeof dateTime === 'string') dateTime = new Date(dateTime)
  return moment(dateTime).fromNow()
}

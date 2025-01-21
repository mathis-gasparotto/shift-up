import { Notify } from 'quasar'

export function notify(type, message, spinner = false, timeout = 3000) {
  return Notify.create({
    spinner: spinner,
    message,
    color: type === 'info' ? 'primary' : type === 'success' ? 'positive' : type === 'error' ? 'negative' : 'warning',
    icon: spinner ? null : type === 'success' ? 'check_circle' : type === 'error' ? 'report_problem' : 'warning',
    position: 'top',
    timeout: timeout,
    actions: [
      {
        icon: 'close',
        color: 'white'
      }
    ]
  })
}

export function successNotify(message, timeout = 3000) {
  return notify('success', message, false, timeout)
}

export function loadingNotify(message, timeout = 3000) {
  return notify('info', message, true, timeout)
}

export function errorNotify(message, timeout = 3000) {
  return notify('error', message, false, timeout)
}

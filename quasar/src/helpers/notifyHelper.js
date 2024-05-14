import { Notify } from 'quasar'

export function notify(type, message) {
  Notify.create({
    message,
    color: type === 'success' ? 'positive' : type === 'error' ? 'negative' : 'warning',
    icon: type === 'success' ? 'check_circle' : type === 'error' ? 'report_problem' : 'warning',
    position: 'top',
    timeout: 3000,
    actions: [
      {
        icon: 'close',
        color: 'white'
      }
    ]
  })
}

export function successNotify(message) {
  notify('success', message)
}

export function errorNotify(message) {
  notify('error', message)
}

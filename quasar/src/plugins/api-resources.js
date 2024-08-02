import createResource from 'src/services/resource'

export default function resources(axios /*, sentry*/) {
  const axiosResource = createResource(axios /*, sentry*/)
  return {
    users: axiosResource('users'),
    teams: axiosResource('teams'),
    projects: axiosResource('projects'),
    forgotPassword: axiosResource('send_reset_password'),
    resetPassword: axiosResource('reset_password'),
  }
}

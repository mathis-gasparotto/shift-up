import createResource from 'src/services/resource'

export default function resources(axios /*, sentry*/) {
  const axiosResource = createResource(axios /*, sentry*/)
  return {
    users: axiosResource('users'),
    teams: axiosResource('teams')
  }
}

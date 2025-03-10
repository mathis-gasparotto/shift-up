import createResource from 'src/services/resource'

export default function resources(axios /*, sentry*/) {
  const axiosResource = createResource(axios /*, sentry*/)
  return {
    users: axiosResource('users'),
    teams: axiosResource('teams'),
    projects: axiosResource('projects'),
    subscriptions: axiosResource('subscriptions'),
    forgotPassword: axiosResource('send_reset_password'),
    resetPassword: axiosResource('reset_password'),
    businessModelCanvas: axiosResource('business_model_canvas'),
    buyerPersonas: axiosResource('buyer_personas'),
    competitorAnalyses: axiosResource('competitor_analyses'),
    goldenTriangles: axiosResource('golden_triangles'),
    marketingMix4s: axiosResource('marketing_mix_4s'),
    marketingMix5s: axiosResource('marketing_mix_5s'),
    pestels: axiosResource('pestels'),
    smarts: axiosResource('smarts'),
    stps: axiosResource('stps'),
    swots: axiosResource('swots'),
    checkUsers: axiosResource('check_users')
  }
}

import { boot } from 'quasar/wrappers'
import axios from 'axios'
import { useAuthStore } from 'src/store/auth'

// Be careful when using SSR for cross-request state pollution
// due to creating a Singleton instance here;
// If any client changes this (global) instance, it might be a
// good idea to move this instance creation inside of the
// "export default () => {}" function below (which runs individually
// for each client)
const api = axios.create({ baseURL: process.env.API_URL })

export default boot(({ app, redirect, urlPath }) => {
  const auth = useAuthStore()

  api.interceptors.response.use(
    (res) => {
      return res
    },
    async (error) => {
      const originalConfig = error.config
      if (originalConfig.url !== '/authenticate' && originalConfig.url !== '/token/refresh' && error.response) {
        if (error.response.status === 401) {
          try {
            const refresh = await api.post('/token/refresh', {
              refresh_token: auth.getRefreshToken()
            })
            auth.setToken(refresh.data.token)
            auth.setRefreshToken(refresh.data.refresh_token)

            return api(originalConfig)
          } catch (_error) {
            auth.logout()
            redirect({ name: 'signin' })
          }
        }
      }

      return Promise.reject(error)
    }
  )

  api.interceptors.request.use(
    (config) => {
      if (auth.isAuthenticated && config.url !== '/token/refresh') {
        config.headers.Authorization = `Bearer ${auth.getToken()}`
      }
      return config
    },
    (error) => {
      return Promise.reject(error)
    }
  )

  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
  app.config.globalProperties.$auth = auth
})

export { api }

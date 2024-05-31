import { defineStore } from 'pinia'
import { LocalStorage } from 'quasar'
import { api } from 'src/boot/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: LocalStorage.getItem('token'),
    me: null,
    loading: true
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token),
    getUser: (state) => state.me,
    isLoading: (state) => Boolean(state.token) && state.loading
  },
  actions: {
    login(email, password) {
      return api.post('/authenticate', { email, password }).then((res) => {
        this.setToken(res.data.token)
        this.setRefreshToken(res.data.refresh_token)

        return this.loadUserData()
      })
    },
    signup(payload) {
      return api.post('/register', payload).then((res) => {
        this.setMe(res.data)
        return res.data
      })
    },
    logout() {
      this.token = null
      this.me = null
      LocalStorage.remove('token')
      LocalStorage.remove('refreshToken')
    },
    loadUserData(cached = true) {
      this.loading = true
      return new Promise((resolve, reject) => {
        if (!cached || !this.me) {
          return api.get('/users/me').then(
            (res) => {
              this.setMe(res.data)
              this.loading = false
              return resolve(this.me)
            },
            (error) => {
              this.loading = false
              return reject(error)
            }
          )
        }
        this.loading = false
        return resolve(this.me)
      })
    },
    setToken(token) {
      this.token = token
      LocalStorage.set('token', token)
    },
    getToken() {
      return LocalStorage.getItem('token')
    },
    setRefreshToken(refreshToken) {
      LocalStorage.set('refreshToken', refreshToken)
    },
    getRefreshToken() {
      return LocalStorage.getItem('refreshToken')
    },
    setMe(me) {
      this.me = me
    },
    setCacheDirty() {
      this.me = null
    },
    confirmEmail(token) {
      return api.post('/verify_email_register/' + token)
    }
  }
})

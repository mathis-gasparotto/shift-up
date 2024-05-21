import { defineStore } from 'pinia'
import { LocalStorage } from 'quasar'
import { api } from 'src/boot/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: LocalStorage.getItem('token'),
    me: null
  }),
  getters: {
    isAuthenticated: (state) => Boolean(state.token),
    getUser: (state) => state.me
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
      return new Promise((resolve, reject) => {
        if (!cached || !this.me) {
          return api.get('/users/me').then(
            (res) => {
              this.setMe(res.data)
              return resolve(this.me)
            },
            (error) => {
              return reject(error)
            }
          )
        }
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
    }
  }
})

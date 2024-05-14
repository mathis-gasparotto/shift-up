import { useAuthStore } from 'src/store/auth'

const beforeSign = function (to, from, next) {
  const auth = useAuthStore()

  if (auth.isAuthenticated) {
    return next({
      name: from.name === 'signin' || from.name === 'signup' ? 'index' : from.name
    })
  } else {
    return next()
  }
}

const beforeNoSign = function (to, from, next) {
  const auth = useAuthStore()

  if (!auth.isAuthenticated) {
    return next({
      name: 'signin'
    })
  } else {
    return next()
  }
}

const routes = [
  {
    path: '/sign',
    component: () => import('layouts/SignLayout.vue'),
    beforeEnter: beforeSign,
    children: [
      {
        path: 'signup',
        name: 'signup',
        component: () => import('pages/Sign/SignUp.vue')
      },
      {
        path: 'signin',
        name: 'signin',
        component: () => import('pages/Sign/SignIn.vue')
      }
    ]
  },
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    beforeEnter: beforeNoSign,
    children: [
      {
        path: '',
        name: 'index',
        component: () => import('pages/IndexPage.vue')
      },
      {
        path: 'test',
        name: 'test',
        component: () => import('pages/ErrorNotFound.vue')
      }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes

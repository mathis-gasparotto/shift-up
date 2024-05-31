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
        component: () => import('pages/sign/signup.vue')
      },
      {
        path: 'signin',
        name: 'signin',
        component: () => import('pages/sign/signin.vue')
      }
    ]
  },
  {
    path: '/verify-email/:token',
    name: 'email-confirmation',
    component: () => import('pages/email-confirmation.vue')
  },
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    beforeEnter: beforeNoSign,
    children: [
      {
        path: '',
        name: 'index',
        component: () => import('pages/index.vue')
      },
      {
        path: 'teams/:teamId',
        name: 'team',
        component: () => import('pages/teams/_teamId.vue')
      },
      {
        path: 'test',
        name: 'test',
        component: () => import('pages/not-found.vue')
      }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/not-found.vue')
  }
]

export default routes

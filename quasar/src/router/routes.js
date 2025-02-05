import { useAuthStore } from 'src/store/auth'

function beforeSign(to, from, next) {
  const auth = useAuthStore()

  if (auth.isAuthenticated) {
    return next({
      name: from.name === 'signin' || from.name === 'signup' ? 'index' : from.name
    })
  } else {
    return next()
  }
}

function beforeNoSign(to, from, next) {
  const auth = useAuthStore()

  if (!auth.isAuthenticated) {
    if (to.fullPath && to.fullPath != '/' && to.fullPath != '') {
      return next({
        name: 'signin',
        query: { redirect: to.fullPath }
      })
    }
    return next({
      name: 'signin'
    })
  } else {
    return next()
  }
}

const routes = [
  {
    path: '/signup',
    component: () => import('layouts/SignLayout.vue'),
    beforeEnter: beforeSign,
    children: [
      {
        path: '',
        name: 'signup',
        component: () => import('pages/sign/signup.vue')
      }
    ]
  },
  {
    path: '/signin',
    component: () => import('layouts/SignLayout.vue'),
    beforeEnter: beforeSign,
    children: [
      {
        path: '',
        name: 'signin',
        component: () => import('pages/sign/signin.vue')
      }
    ]
  },
  {
    path: '/forgot-password',
    component: () => import('layouts/SignLayout.vue'),
    beforeEnter: beforeSign,
    children: [
      {
        path: '',
        name: 'forgotPassword',
        component: () => import('pages/sign/forgot-password.vue')
      }
    ]
  },
  {
    path: '/reset-password/:token',
    component: () => import('layouts/SignLayout.vue'),
    beforeEnter: beforeSign,
    children: [
      {
        path: '',
        name: 'resetPassword',
        component: () => import('pages/sign/reset-password.vue')
      }
    ]
  },
  {
    path: '/email-confirmation/:token',
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
        name: 'home',
        component: () => import('pages/index.vue')
      },
      {
        path: 'profile',
        name: 'profile',
        component: () => import('pages/profile.vue')
      },
      {
        path: 'teams/:teamId',
        name: 'team',
        component: () => import('pages/teams/_teamId/index.vue')
      },
      {
        path: 'teams/:teamId/projects/create',
        name: 'project-create',
        component: () => import('pages/teams/_teamId/projects/create.vue')
      },
      {
        path: 'teams/:teamId/projects/:projectId',
        name: 'project',
        component: () => import('pages/teams/_teamId/projects/_projectId/index.vue')
      },
      {
        path: 'teams/:teamId/projects/:projectId/:documentName',
        name: 'project-document',
        component: () => import('pages/teams/_teamId/projects/_projectId/_documentName.vue')
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

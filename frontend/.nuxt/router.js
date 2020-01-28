import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _e84386c2 = () => interopDefault(import('..\\pages\\dashboard.vue' /* webpackChunkName: "pages_dashboard" */))
const _1ffdfeba = () => interopDefault(import('..\\pages\\dashboard2.vue' /* webpackChunkName: "pages_dashboard2" */))
const _622db4c3 = () => interopDefault(import('..\\pages\\dashboardOps.vue' /* webpackChunkName: "pages_dashboardOps" */))
const _1e875022 = () => interopDefault(import('..\\pages\\data.vue' /* webpackChunkName: "pages_data" */))
const _4ec4ff18 = () => interopDefault(import('..\\pages\\login.vue' /* webpackChunkName: "pages_login" */))
const _4a0497aa = () => interopDefault(import('..\\pages\\organizations.vue' /* webpackChunkName: "pages_organizations" */))
const _7ef853e8 = () => interopDefault(import('..\\pages\\register.vue' /* webpackChunkName: "pages_register" */))
const _0a2dd70a = () => interopDefault(import('..\\pages\\reports.vue' /* webpackChunkName: "pages_reports" */))
const _049c2168 = () => interopDefault(import('..\\pages\\settings.vue' /* webpackChunkName: "pages_settings" */))
const _7191e5b6 = () => interopDefault(import('..\\pages\\admin\\organizations.vue' /* webpackChunkName: "pages_admin_organizations" */))
const _5a77a41e = () => interopDefault(import('..\\pages\\admin\\users.vue' /* webpackChunkName: "pages_admin_users" */))
const _0ef5dd5d = () => interopDefault(import('..\\pages\\index.vue' /* webpackChunkName: "pages_index" */))

// TODO: remove in Nuxt 3
const emptyFn = () => {}
const originalPush = Router.prototype.push
Router.prototype.push = function push (location, onComplete = emptyFn, onAbort) {
  return originalPush.call(this, location, onComplete, onAbort)
}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: decodeURI('/'),
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/dashboard",
    component: _e84386c2,
    name: "dashboard"
  }, {
    path: "/dashboard2",
    component: _1ffdfeba,
    name: "dashboard2"
  }, {
    path: "/dashboardOps",
    component: _622db4c3,
    name: "dashboardOps"
  }, {
    path: "/data",
    component: _1e875022,
    name: "data"
  }, {
    path: "/login",
    component: _4ec4ff18,
    name: "login"
  }, {
    path: "/organizations",
    component: _4a0497aa,
    name: "organizations"
  }, {
    path: "/register",
    component: _7ef853e8,
    name: "register"
  }, {
    path: "/reports",
    component: _0a2dd70a,
    name: "reports"
  }, {
    path: "/settings",
    component: _049c2168,
    name: "settings"
  }, {
    path: "/admin/organizations",
    component: _7191e5b6,
    name: "admin-organizations"
  }, {
    path: "/admin/users",
    component: _5a77a41e,
    name: "admin-users"
  }, {
    path: "/",
    component: _0ef5dd5d,
    name: "index"
  }],

  fallback: false
}

export function createRouter () {
  return new Router(routerOptions)
}

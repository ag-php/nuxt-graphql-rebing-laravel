import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _633551af = () => interopDefault(import('..\\pages\\boxesAssignment.vue' /* webpackChunkName: "pages_boxesAssignment" */))
const _1531fa9d = () => interopDefault(import('..\\pages\\dashboard.vue' /* webpackChunkName: "pages_dashboard" */))
const _ddb5fb36 = () => interopDefault(import('..\\pages\\dashboard2.vue' /* webpackChunkName: "pages_dashboard2" */))
const _4a584e05 = () => interopDefault(import('..\\pages\\dashboardOps.vue' /* webpackChunkName: "pages_dashboardOps" */))
const _366d5331 = () => interopDefault(import('..\\pages\\data.vue' /* webpackChunkName: "pages_data" */))
const _490bed72 = () => interopDefault(import('..\\pages\\login.vue' /* webpackChunkName: "pages_login" */))
const _0fb379ae = () => interopDefault(import('..\\pages\\organizations.vue' /* webpackChunkName: "pages_organizations" */))
const _4155de2a = () => interopDefault(import('..\\pages\\register.vue' /* webpackChunkName: "pages_register" */))
const _18b4fc88 = () => interopDefault(import('..\\pages\\reports.vue' /* webpackChunkName: "pages_reports" */))
const _720ca8ac = () => interopDefault(import('..\\pages\\settings.vue' /* webpackChunkName: "pages_settings" */))
const _a08fc610 = () => interopDefault(import('..\\pages\\admin\\organizations.vue' /* webpackChunkName: "pages_admin_organizations" */))
const _42a23d60 = () => interopDefault(import('..\\pages\\admin\\users.vue' /* webpackChunkName: "pages_admin_users" */))
const _7f644a5b = () => interopDefault(import('..\\pages\\index.vue' /* webpackChunkName: "pages_index" */))

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
    path: "/boxesAssignment",
    component: _633551af,
    name: "boxesAssignment"
  }, {
    path: "/dashboard",
    component: _1531fa9d,
    name: "dashboard"
  }, {
    path: "/dashboard2",
    component: _ddb5fb36,
    name: "dashboard2"
  }, {
    path: "/dashboardOps",
    component: _4a584e05,
    name: "dashboardOps"
  }, {
    path: "/data",
    component: _366d5331,
    name: "data"
  }, {
    path: "/login",
    component: _490bed72,
    name: "login"
  }, {
    path: "/organizations",
    component: _0fb379ae,
    name: "organizations"
  }, {
    path: "/register",
    component: _4155de2a,
    name: "register"
  }, {
    path: "/reports",
    component: _18b4fc88,
    name: "reports"
  }, {
    path: "/settings",
    component: _720ca8ac,
    name: "settings"
  }, {
    path: "/admin/organizations",
    component: _a08fc610,
    name: "admin-organizations"
  }, {
    path: "/admin/users",
    component: _42a23d60,
    name: "admin-users"
  }, {
    path: "/",
    component: _7f644a5b,
    name: "index"
  }],

  fallback: false
}

export function createRouter () {
  return new Router(routerOptions)
}

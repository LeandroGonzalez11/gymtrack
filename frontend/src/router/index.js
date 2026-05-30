/**
 * GymTrack · router/index.js
 * -------------------------------------------------
 * Rutas de la aplicación con guards de navegación.
 *
 * meta.requiereAuth  = true  → redirige a /login si no hay token
 * meta.soloPublico   = true  → redirige a /dashboard si ya está logueado
 */

import { createRouter, createWebHistory } from 'vue-router'
import HomeView      from '../views/HomeView.vue'
import LoginView     from '../views/LoginView.vue'
import RegisterView  from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import AdminView     from '../views/AdminView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: { soloPublico: true },
  },
  {
    path: '/registro',
    name: 'registro',
    component: RegisterView,
    meta: { soloPublico: true },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: DashboardView,
    meta: { requiereAuth: true },
  },
  {
    path: '/admin',
    name: 'admin',
    component: AdminView,
    meta: { requiereAuth: true },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// ── Guard global ──────────────────────────────────────────────
router.beforeEach((to) => {
  const token = localStorage.getItem('token')

  // Ruta protegida sin sesión → mandamos al login
  if (to.meta.requiereAuth && !token) {
    return { name: 'login' }
  }

  // Ruta pública (login/registro) con sesión activa → mandamos al dashboard
  if (to.meta.soloPublico && token) {
    return { name: 'dashboard' }
  }
})

export default router

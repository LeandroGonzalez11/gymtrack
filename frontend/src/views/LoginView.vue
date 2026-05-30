<template>
  <div class="auth-page">
    <router-link to="/" class="brand">
      <img class="brand-mark" src="../assets/gymtrack-mark.svg" alt="GymTrack" />
      <span class="brand-word">Gym<span>Track</span></span>
    </router-link>

    <main class="auth-card">
      <p class="kicker">Acceso seguro</p>
      <h1>Bienvenido de vuelta</h1>
      <p class="muted">Ingresa tus credenciales para entrar al panel de GymTrack.</p>

      <div v-if="error" class="alert">{{ error }}</div>

      <form @submit.prevent="submitLogin">
        <label>
          Correo electronico
          <input v-model="form.email" type="email" placeholder="admin@gmail.com" required :disabled="cargando" />
        </label>
        <label>
          Contrasena
          <input v-model="form.password" type="password" placeholder="admin123" required :disabled="cargando" />
        </label>
        <button type="submit" class="primary-btn" :disabled="cargando">
          {{ cargando ? 'Ingresando...' : 'Ingresar' }}
        </button>
      </form>

      <p class="auth-footer">
        No tenes cuenta?
        <router-link to="/registro">Registrate</router-link>
      </p>
    </main>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()
const form = reactive({ email: '', password: '' })
const cargando = ref(false)
const error = ref(null)

async function submitLogin() {
  error.value = null
  cargando.value = true
  try {
    await authStore.login(form.email, form.password)
    router.push({ name: 'dashboard' })
  } catch (e) {
    error.value = e.message
  } finally {
    cargando.value = false
  }
}
</script>

<style scoped>
.auth-page {
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 24px;
  background:
    linear-gradient(90deg, rgba(2, 6, 23, 0.96), rgba(2, 6, 23, 0.7)),
    url('https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=1500&q=85') center/cover;
}

.brand {
  position: fixed;
  top: 24px;
  left: 24px;
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
}

.auth-card {
  width: min(440px, 100%);
  border: 1px solid rgba(56, 189, 248, 0.28);
  border-radius: 8px;
  padding: 34px;
  background: rgba(7, 17, 31, 0.88);
  box-shadow: 0 30px 90px rgba(0, 0, 0, 0.42);
  backdrop-filter: blur(18px);
}

h1 {
  margin: 8px 0 8px;
  font-size: 2rem;
}

form {
  display: grid;
  gap: 16px;
  margin-top: 24px;
}

label {
  display: grid;
  gap: 8px;
  color: var(--text);
  font-weight: 800;
}

input {
  min-height: 46px;
  border: 1px solid var(--line);
  border-radius: 8px;
  padding: 0 12px;
  background: rgba(2, 6, 23, 0.72);
  color: var(--text);
}

input:focus {
  outline: none;
  border-color: var(--blue-2);
  box-shadow: 0 0 0 4px rgba(0, 119, 255, 0.16);
}

.primary-btn {
  width: 100%;
}

.alert {
  margin-top: 18px;
  border: 1px solid rgba(239, 68, 68, 0.38);
  border-radius: 8px;
  padding: 12px;
  background: rgba(239, 68, 68, 0.12);
}

.auth-footer {
  margin-top: 18px;
  color: var(--muted);
}

.auth-footer a {
  color: var(--blue-2);
  font-weight: 900;
  text-decoration: none;
}
</style>

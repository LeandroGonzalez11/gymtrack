<template>
  <div class="home-page">
    <header class="site-nav">
      <router-link to="/" class="brand">
        <img class="brand-mark" src="../assets/gymtrack-mark.svg" alt="GymTrack" />
        <span class="brand-word">Gym<span>Track</span></span>
      </router-link>
      <nav>
        <a href="#funciones">Funciones</a>
        <a href="#planes">Planes</a>
        <a href="#gimnasios">Gimnasios</a>
        <a href="#owners">Owners</a>
      </nav>
      <div class="nav-actions">
        <template v-if="authStore.estaAutenticado">
          <router-link to="/dashboard" class="ghost-btn">Dashboard</router-link>
          <button class="primary-btn" @click="handleLogout">Salir</button>
        </template>
        <template v-else>
          <router-link to="/login" class="ghost-btn">Iniciar sesion</router-link>
          <router-link to="/registro" class="primary-btn">Registrarse</router-link>
        </template>
      </div>
    </header>

    <main class="hero">
      <section class="hero-copy">
        <p class="kicker">Plataforma multi-gym SaaS</p>
        <h1><span>Gestiona.</span><span>Conecta.</span><span>Crece.</span><strong>Gym<span>Track</span></strong></h1>
        <p>
          Una plataforma premium para que gimnasios independientes administren socios,
          membresias, clases, reservas y pagos desde una experiencia clara y moderna.
        </p>
        <div class="hero-actions">
          <router-link to="/registro" class="primary-btn">Buscar gimnasios</router-link>
          <a href="#owners" class="ghost-btn">Soy dueno de gimnasio</a>
        </div>
      </section>

      <aside class="finder-panel panel-card" id="gimnasios">
        <div class="panel-title">
          <div>
            <span class="kicker">Explorar</span>
            <h2>Gimnasios cerca tuyo</h2>
          </div>
          <button class="locate-btn" @click="locateUser" title="Detectar ubicacion">⌖</button>
        </div>
        <div class="filters">
          <input v-model="search" type="search" placeholder="Buscar por nombre o ciudad" />
          <select v-model="city">
            <option value="">Todas las ciudades</option>
            <option v-for="name in cities" :key="name" :value="name">{{ name }}</option>
          </select>
          <select v-model="category">
            <option value="">Todas las categorias</option>
            <option v-for="name in categories" :key="name" :value="name">{{ name }}</option>
          </select>
        </div>
        <div ref="mapEl" class="map-shell">
          <span class="map-status">{{ mapStatus }}</span>
          <span
            v-if="!mapReady"
            v-for="gym in filteredGyms"
            :key="gym.id"
            class="map-pin"
            :style="{ left: `${pinPosition(gym).x}%`, top: `${pinPosition(gym).y}%` }"
            :title="gym.name"
          ></span>
        </div>
        <article v-for="gym in filteredGyms.slice(0, 2)" :key="gym.id" class="gym-result" @click="openPublicGym(gym)">
          <img :src="gym.image" :alt="gym.name" />
          <div>
            <strong>{{ gym.name }}</strong>
            <span>{{ gym.city }} · {{ gym.category }}</span>
            <small>{{ gym.price }}/mes · {{ gym.classes.join(', ') }}</small>
          </div>
        </article>
      </aside>
    </main>

    <section class="stats-strip">
      <article><strong>+150</strong><span>Gimnasios activos</span></article>
      <article><strong>+12.500</strong><span>Socios conectados</span></article>
      <article><strong>+25.000</strong><span>Reservas realizadas</span></article>
      <article><strong>24/7</strong><span>Operacion SaaS</span></article>
    </section>

    <section class="section-grid" id="funciones">
      <div class="section-copy">
        <span class="kicker">Gestion completa</span>
        <h2>Todo lo necesario para una red real de gimnasios</h2>
      </div>
      <article v-for="feature in features" :key="feature.title" class="feature-card">
        <span>{{ feature.icon }}</span>
        <h3>{{ feature.title }}</h3>
        <p>{{ feature.text }}</p>
      </article>
    </section>

    <section class="showcase">
      <div class="gym-grid">
        <article v-for="gym in gyms" :key="gym.id" class="gym-card" :style="{ '--gym-accent': gym.brandColor }">
          <img :src="gym.image" :alt="gym.name" />
          <div>
            <b class="gym-logo">{{ gym.logoText }}</b>
            <strong>{{ gym.name }}</strong>
            <span>{{ gym.city }} · {{ gym.plan }}</span>
          </div>
          <button class="text-btn" @click="openPublicGym(gym)">Ver</button>
        </article>
      </div>
      <div class="owner-copy" id="owners">
        <span class="kicker">Para gym owners</span>
        <h2>Tu gimnasio, tus datos, tu comunidad</h2>
        <p>
          Cada gimnasio administra sus clases, socios, membresias, cupos y pagos internos.
          GymTrack opera como plataforma SaaS, no como un gimnasio unico.
        </p>
        <router-link to="/registro" class="primary-btn">Crear gimnasio demo</router-link>
      </div>
    </section>

    <div v-if="publicGym" class="modal-backdrop" @click.self="publicGym = null">
      <article class="modal-card">
        <button class="modal-close" @click="publicGym = null">Cerrar</button>
        <img :src="publicGym.image" :alt="publicGym.name" />
        <div class="modal-title">
          <span class="gym-logo big" :style="{ background: publicGym.brandColor }">{{ publicGym.logoText }}</span>
          <div>
            <h2>{{ publicGym.name }}</h2>
            <p class="muted">{{ publicGym.city }} · {{ publicGym.category }} · {{ publicGym.price }}/mes</p>
          </div>
        </div>
        <div class="modal-grid">
          <div>
            <strong>Clases que podes revisar sin cuenta</strong>
            <span v-for="item in publicGym.classes" :key="item">{{ item }}</span>
          </div>
          <div>
            <strong>Para unirte o reservar</strong>
            <span>Necesitas crear una cuenta de socio.</span>
            <span>La membresia se coordina con el gimnasio.</span>
          </div>
        </div>
        <div class="modal-actions">
          <router-link to="/registro" class="primary-btn">Registrarme como socio</router-link>
          <router-link to="/registro" class="ghost-btn">Soy dueno de gimnasio</router-link>
        </div>
      </article>
    </div>

    <footer class="footer">
      <div class="brand">
        <img class="brand-mark large" src="../assets/gymtrack-mark.svg" alt="GymTrack" />
        <span class="brand-word">Gym<span>Track</span></span>
      </div>
      <span>Vue 3 · PHP · MySQL · Docker · Leaflet/OpenStreetMap</span>
    </footer>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { gyms } from '../data/demoData'

const authStore = useAuthStore()
const router = useRouter()
const search = ref('')
const city = ref('')
const category = ref('')
const mapEl = ref(null)
const mapStatus = ref('Mapa demo con OpenStreetMap')
const mapReady = ref(false)
const publicGym = ref(null)

const cities = computed(() => [...new Set(gyms.map((gym) => gym.city))])
const categories = computed(() => [...new Set(gyms.map((gym) => gym.category))])
const filteredGyms = computed(() => {
  const term = search.value.toLowerCase().trim()
  return gyms.filter((gym) => {
    const matchesTerm = !term || `${gym.name} ${gym.city}`.toLowerCase().includes(term)
    const matchesCity = !city.value || gym.city === city.value
    const matchesCategory = !category.value || gym.category === category.value
    return matchesTerm && matchesCity && matchesCategory
  })
})

const features = [
  { icon: '▦', title: 'Multigimnasios', text: 'Gestiona varios gimnasios con owners, socios y datos separados.' },
  { icon: '⌖', title: 'Geolocalizacion', text: 'Busqueda por ciudad y deteccion de ubicacion para gimnasios cercanos.' },
  { icon: '□', title: 'Reservas y clases', text: 'Cupos, profesores, horarios y reservas en una experiencia simple.' },
  { icon: '$', title: 'Membresias y pagos', text: 'Planes demo, vencimientos y estados para defender el flujo SaaS.' },
]

function pinPosition(gym) {
  const index = gyms.findIndex((item) => item.id === gym.id)
  return { x: 18 + index * 15, y: 34 + (index % 3) * 14 }
}

function locateUser() {
  if (!navigator.geolocation) {
    mapStatus.value = 'Geolocalizacion no disponible en este navegador'
    return
  }
  mapStatus.value = 'Detectando ubicacion...'
  navigator.geolocation.getCurrentPosition(
    () => { mapStatus.value = 'Ubicacion detectada · mostrando gimnasios cercanos' },
    () => { mapStatus.value = 'No se pudo detectar ubicacion · mostrando demo por ciudad' },
    { timeout: 4500 }
  )
}

function openPublicGym(gym) {
  publicGym.value = gym
}

async function handleLogout() {
  await authStore.logout()
  router.push({ name: 'home' })
}

onMounted(() => {
  if (window.L && mapEl.value) {
    const map = window.L.map(mapEl.value, { zoomControl: false, attributionControl: false }).setView([-32.36, -54.18], 7)
    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map)
    gyms.forEach((gym) => window.L.marker([gym.lat, gym.lng]).addTo(map).bindPopup(`${gym.name} · ${gym.city}`))
    mapReady.value = true
    mapStatus.value = 'OpenStreetMap activo'
  }
})
</script>

<style scoped>
.home-page {
  min-height: 100vh;
  padding: 22px;
  background:
    radial-gradient(circle at 28% 8%, rgba(56, 189, 248, 0.12), transparent 26rem),
    radial-gradient(circle at 78% 18%, rgba(0, 119, 255, 0.1), transparent 28rem),
    #05070a;
}

.site-nav,
.hero,
.stats-strip,
.section-grid,
.showcase,
.footer {
  width: min(1180px, 100%);
  margin: 0 auto;
}

.site-nav {
  display: flex;
  min-height: 64px;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  border: 1px solid rgba(56, 189, 248, 0.34);
  border-radius: 12px;
  padding: 12px 16px;
  background: rgba(6, 10, 16, 0.78);
  backdrop-filter: blur(18px);
  box-shadow: 0 0 34px rgba(0, 119, 255, 0.12);
  animation: fadeDown 0.55s ease both;
}

.brand,
.nav-actions,
nav {
  display: flex;
  align-items: center;
  gap: 14px;
}

nav a {
  color: var(--muted);
  font-size: 0.76rem;
  font-weight: 800;
  text-decoration: none;
  text-transform: uppercase;
}

nav a:hover {
  color: var(--text);
}

.hero {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 390px;
  gap: 24px;
  align-items: center;
  min-height: 710px;
  padding: 22px 0 22px;
  border: 1px solid rgba(56, 189, 248, 0.28);
  border-radius: 12px;
  margin-top: 18px;
  padding-inline: 34px;
  background:
    linear-gradient(90deg, rgba(5, 7, 10, 0.97) 0%, rgba(5, 7, 10, 0.82) 34%, rgba(5, 7, 10, 0.46) 62%, rgba(5, 7, 10, 0.9) 100%),
    url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1600&q=90') center/cover;
  box-shadow: inset 0 0 80px rgba(0, 119, 255, 0.1), 0 24px 90px rgba(0, 0, 0, 0.36);
}

.hero-copy {
  min-height: 560px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 26px 0 26px 28px;
  animation: riseIn 0.65s ease both;
}

.hero h1 {
  max-width: 680px;
  margin: 14px 0 20px;
  font-family: Montserrat, Inter, sans-serif;
  font-size: clamp(3.1rem, 6vw, 5.8rem);
  line-height: 0.92;
  letter-spacing: 0;
  text-transform: uppercase;
}

.hero h1 span,
.hero h1 strong {
  display: block;
}

.hero h1 strong span {
  display: inline;
  color: var(--blue);
}

.hero-copy p:not(.kicker) {
  max-width: 590px;
  color: #dbeafe;
  font-size: 1.05rem;
  line-height: 1.7;
}

.hero-actions {
  display: flex;
  gap: 14px;
  margin-top: 28px;
}

.finder-panel {
  align-self: stretch;
  display: flex;
  flex-direction: column;
  gap: 14px;
  max-height: 510px;
  margin: auto 0;
  background: rgba(7, 12, 19, 0.74);
  backdrop-filter: blur(20px);
  box-shadow: 0 0 40px rgba(0, 119, 255, 0.1);
  animation: slideIn 0.7s ease 0.08s both;
}

.panel-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}

.panel-title h2 {
  margin-top: 5px;
  font-family: Montserrat, Inter, sans-serif;
  font-size: 1.15rem;
  text-transform: uppercase;
}

.locate-btn {
  width: 42px;
  height: 42px;
  border: 1px solid var(--line);
  border-radius: 8px;
  background: rgba(0, 119, 255, 0.16);
  color: var(--blue-2);
  cursor: pointer;
}

.filters {
  display: grid;
  gap: 10px;
}

.filters input,
.filters select {
  min-height: 42px;
  border: 1px solid var(--line);
  border-radius: 8px;
  padding: 0 12px;
  background: rgba(2, 6, 23, 0.72);
  color: var(--text);
}

.map-shell {
  position: relative;
  min-height: 190px;
  overflow: hidden;
  border: 1px solid rgba(56, 189, 248, 0.22);
  border-radius: 8px;
  background:
    linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.58)),
    url('https://tile.openstreetmap.org/7/45/77.png') center/cover;
}

.map-status {
  position: absolute;
  left: 12px;
  bottom: 10px;
  z-index: 402;
  border-radius: 999px;
  padding: 6px 10px;
  background: rgba(2, 6, 23, 0.78);
  color: #dbeafe;
  font-size: 0.75rem;
}

.map-pin {
  position: absolute;
  z-index: 401;
  width: 18px;
  height: 18px;
  border: 3px solid white;
  border-radius: 999px 999px 999px 2px;
  background: var(--blue);
  transform: rotate(-45deg);
  box-shadow: 0 0 24px rgba(0, 119, 255, 0.7);
}

.gym-result,
.gym-card {
  display: grid;
  grid-template-columns: 72px 1fr;
  gap: 12px;
  align-items: center;
  border: 1px solid var(--line);
  border-radius: 8px;
  padding: 10px;
  background: rgba(15, 23, 42, 0.66);
  cursor: pointer;
}

.gym-result img,
.gym-card img {
  width: 72px;
  height: 58px;
  border-radius: 6px;
  object-fit: cover;
  filter: saturate(0.92);
}

.gym-result span,
.gym-result small,
.gym-card span,
.gym-card small {
  display: block;
  margin-top: 4px;
  color: var(--muted);
}

.stats-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-top: -96px;
  margin-bottom: 42px;
  position: relative;
  z-index: 4;
  max-width: 720px;
  margin-left: calc((100% - min(1180px, 100%)) / 2 + 64px);
}

.stats-strip article {
  border: 1px solid var(--line);
  border-radius: 8px;
  padding: 18px;
  background: rgba(10, 17, 26, 0.9);
  box-shadow: 0 16px 44px rgba(0, 0, 0, 0.35);
  animation: riseIn 0.55s ease both;
}

.stats-strip article:nth-child(2) { animation-delay: 0.05s; }
.stats-strip article:nth-child(3) { animation-delay: 0.1s; }
.stats-strip article:nth-child(4) { animation-delay: 0.15s; }

.stats-strip strong {
  display: block;
  font-size: 1.6rem;
}

.stats-strip span {
  color: var(--muted);
  font-size: 0.82rem;
  font-weight: 800;
  text-transform: uppercase;
}

.section-grid {
  display: grid;
  grid-template-columns: 1.2fr repeat(4, 1fr);
  gap: 14px;
  align-items: stretch;
  margin-bottom: 42px;
}

.section-copy,
.feature-card,
.owner-copy {
  border: 1px solid var(--line);
  border-radius: 8px;
  padding: 22px;
  background: rgba(15, 23, 42, 0.66);
  transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}

.feature-card:hover,
.gym-card:hover,
.owner-copy:hover {
  transform: translateY(-3px);
  border-color: rgba(56, 189, 248, 0.48);
  box-shadow: 0 26px 70px rgba(0, 0, 0, 0.34), 0 0 30px rgba(0, 119, 255, 0.08);
}

.section-copy h2,
.owner-copy h2 {
  margin-top: 8px;
  font-size: clamp(1.7rem, 3vw, 2.7rem);
}

.feature-card span {
  display: grid;
  place-items: center;
  width: 42px;
  height: 42px;
  margin-bottom: 18px;
  border-radius: 8px;
  background: rgba(0, 119, 255, 0.16);
  color: var(--blue-2);
  font-weight: 900;
}

.feature-card h3 {
  margin-bottom: 8px;
}

.feature-card p,
.owner-copy p {
  color: var(--muted);
  line-height: 1.6;
}

.showcase {
  display: grid;
  grid-template-columns: minmax(0, 1.4fr) 0.8fr;
  gap: 18px;
  margin-bottom: 42px;
}

.gym-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 14px;
}

.gym-card {
  grid-template-columns: 86px 1fr auto;
  border-color: color-mix(in srgb, var(--gym-accent, var(--blue)) 38%, rgba(56, 189, 248, 0.22));
}

.gym-logo {
  display: inline-grid;
  place-items: center;
  width: 34px;
  height: 34px;
  margin-bottom: 7px;
  border-radius: 8px;
  background: var(--gym-accent, var(--blue));
  color: #fff;
  font-size: 0.78rem;
  font-weight: 900;
  box-shadow: 0 0 24px color-mix(in srgb, var(--gym-accent, var(--blue)) 35%, transparent);
}

.gym-logo.big {
  width: 54px;
  height: 54px;
  margin: 0;
  font-size: 1rem;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 1000;
  display: grid;
  place-items: center;
  padding: 20px;
  background: rgba(0, 0, 0, 0.72);
  backdrop-filter: blur(10px);
}

.modal-card {
  width: min(660px, 100%);
  border: 1px solid rgba(56, 189, 248, 0.34);
  border-radius: 10px;
  padding: 22px;
  background: linear-gradient(180deg, rgba(16, 24, 36, 0.98), rgba(5, 7, 10, 0.98));
  box-shadow: 0 30px 90px rgba(0, 0, 0, 0.55);
  animation: riseIn 0.25s ease both;
}

.modal-card > img {
  width: 100%;
  height: 230px;
  border-radius: 8px;
  object-fit: cover;
  margin-bottom: 16px;
}

.modal-close {
  float: right;
  border: 1px solid var(--line);
  border-radius: 8px;
  padding: 8px 12px;
  background: rgba(8, 13, 20, 0.9);
  color: var(--text);
  cursor: pointer;
}

.modal-title {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 16px;
}

.modal-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  margin: 18px 0;
}

.modal-grid div {
  border: 1px solid var(--line);
  border-radius: 8px;
  padding: 14px;
  background: rgba(8, 13, 20, 0.64);
}

.modal-grid span {
  display: block;
  margin-top: 8px;
  color: var(--muted);
}

.modal-actions {
  display: flex;
  gap: 12px;
}

@keyframes fadeDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes riseIn {
  from {
    opacity: 0;
    transform: translateY(14px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(18px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.owner-copy {
  display: flex;
  flex-direction: column;
  justify-content: center;
  gap: 18px;
}

.footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  border-top: 1px solid var(--line);
  padding: 24px 0 10px;
  color: var(--muted);
}

.brand-mark.large {
  width: 78px;
  height: 78px;
}

@media (max-width: 1020px) {
  nav {
    display: none;
  }

  .hero,
  .showcase {
    grid-template-columns: 1fr;
  }

  .hero-copy {
    min-height: 460px;
    padding-left: 0;
  }

  .section-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .section-copy {
    grid-column: 1 / -1;
  }
}

@media (max-width: 680px) {
  .home-page {
    padding: 12px;
  }

  .site-nav,
  .nav-actions,
  .hero-actions,
  .footer {
    align-items: stretch;
    flex-direction: column;
  }

  .hero {
    padding-top: 18px;
  }

  .hero-copy {
    min-height: auto;
    padding: 28px;
  }

  .stats-strip,
  .section-grid,
  .modal-grid,
  .gym-grid {
    grid-template-columns: 1fr;
  }

  .modal-actions {
    flex-direction: column;
  }

  .stats-strip {
    max-width: 100%;
    margin: 16px auto 34px;
  }
}
</style>

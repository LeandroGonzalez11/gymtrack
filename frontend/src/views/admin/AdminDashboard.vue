<template>
  <div>
    <h1 class="page-title">Panel de Administración</h1>

    <div v-if="cargando" class="loading">Cargando estadísticas...</div>

    <div v-else>
      <!-- Stats cards -->
      <div class="stats-grid">
        <div class="stat-card" v-for="s in statsCards" :key="s.label">
          <div class="stat-num">{{ s.valor }}</div>
          <div class="stat-label">{{ s.label }}</div>
        </div>
      </div>

      <!-- Accesos rápidos -->
      <div class="accesos">
        <router-link to="/admin/usuarios" class="acceso-card">
          <h3>Gestión de Socios</h3>
          <p>Buscar, filtrar y gestionar membresías de socios.</p>
        </router-link>
        <router-link to="/admin/clases" class="acceso-card">
          <h3>Gestión de Clases</h3>
          <p>Crear, editar y eliminar clases del sistema.</p>
        </router-link>
      </div>

      <!-- Últimos socios registrados -->
      <div class="card">
        <h3 class="card-title">Últimos socios registrados</h3>
        <table v-if="recientes.length">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Email</th>
              <th>Fecha de registro</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="u in recientes" :key="u.id">
              <td>{{ u.nombre }}</td>
              <td>{{ u.email }}</td>
              <td>{{ formatFecha(u.creado_en) }}</td>
            </tr>
          </tbody>
        </table>
        <div v-else class="empty-state">No hay socios registrados todavía.</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '@/services/api'

const stats    = ref({})
const recientes = ref([])
const cargando = ref(true)

onMounted(async () => {
  try {
    const { data } = await api.get('/admin/dashboard')
    stats.value    = data.stats
    recientes.value = data.recientes
  } finally {
    cargando.value = false
  }
})

const statsCards = computed(() => [
  { label: 'Socios activos',      valor: stats.value.total_socios   || 0 },
  { label: 'Membresías activas',  valor: stats.value.mem_activas    || 0 },
  { label: 'Membresías vencidas', valor: stats.value.mem_vencidas   || 0 },
  { label: 'Reservas hoy',        valor: stats.value.reservas_hoy   || 0 },
  { label: 'Clases activas',      valor: stats.value.clases_activas || 0 },
  { label: 'Por vencer (7 días)', valor: stats.value.por_vencer     || 0 },
])

function formatFecha(f) {
  if (!f) return '-'
  return new Date(f).toLocaleDateString('es-UY')
}
</script>

<style scoped>
.page-title { font-size: 24px; margin-bottom: 24px; color: #1C1C1C; }
.loading { padding: 40px; text-align: center; color: #9ca3af; }
.empty-state { padding: 16px 0; color: #9ca3af; font-size: 14px; }

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.stat-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  border-top: 3px solid #38BDF8;
}

.stat-num { font-size: 32px; font-weight: bold; color: #38BDF8; }
.stat-label { font-size: 12px; color: #6b7280; margin-top: 4px; }

.accesos {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 24px;
}

.acceso-card {
  background: #1C1C1C;
  color: white;
  border-radius: 8px;
  padding: 24px;
  text-decoration: none;
  transition: background 0.2s;
}

.acceso-card:hover { background: #2d2d2d; }
.acceso-card h3 { font-size: 16px; margin-bottom: 8px; color: #38BDF8; }
.acceso-card p { font-size: 13px; color: #A0A0A0; }

.card-title { font-size: 16px; font-weight: bold; margin-bottom: 16px; }
</style>

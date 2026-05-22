# frontend/

Esta carpeta contendrá la Single Page Application desarrollada con Vue 3 + Vite.

## Cómo inicializar el proyecto Vue (primera vez)

```bash
# Desde la raíz del repositorio
cd frontend
npm create vue@latest .
npm install
```

## Estructura prevista

```
frontend/
├── src/
│   ├── main.js
│   ├── App.vue
│   ├── router/
│   │   └── index.js        ← Vue Router con guardas de navegación
│   ├── stores/
│   │   └── auth.js         ← Pinia: estado de sesión del usuario
│   ├── services/
│   │   └── api.js          ← Instancia Axios con interceptor de token
│   └── views/
│       ├── HomeView.vue
│       ├── LoginView.vue
│       ├── RegisterView.vue
│       ├── DashboardView.vue
│       ├── ClassesView.vue
│       └── admin/
│           ├── AdminDashboard.vue
│           ├── AdminUsers.vue
│           └── AdminClasses.vue
└── vite.config.js
```

# GymTrack · Sistema de Gestión de Gimnasio

> Vue 3 · PHP · MySQL / MariaDB · Docker  
> Tecnología Web Aplicada · Año lectivo 2026

**Integrantes:** Leandro González · Santiago Cáceres · Máximo Díaz · Emilio Escobar · Magdalena Belmonti · Hiliana Pereira

---

## Estructura del proyecto

```
gymtrack/
├── docker-compose.yml          ← Levanta todo el entorno con un solo comando
├── database/
│   └── gymtrack_database.sql   ← Esquema completo de la base de datos
├── backend/                    ← API REST en PHP (PDO + MVC)
└── frontend/                   ← SPA en Vue 3 + Vite + Pinia
```

---

## Requisitos previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y corriendo
- Git

---

## Cómo levantar el entorno

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/gymtrack.git
cd gymtrack

# 2. Levantar todos los contenedores
docker-compose up -d

# 3. Verificar que todo esté corriendo
docker-compose ps
```

Con eso tenés disponible:

| Servicio   | URL / Puerto                  |
|------------|-------------------------------|
| Frontend   | http://localhost:5173         |
| Backend    | http://localhost:8080         |
| Base de datos | localhost:3306             |

> **Nota:** La base de datos se crea automáticamente con todas las tablas al levantar el contenedor por primera vez. No hace falta ejecutar nada manualmente.

---

## Credenciales de la base de datos (desarrollo)

| Campo    | Valor          |
|----------|----------------|
| Host     | localhost      |
| Puerto   | 3306           |
| Base     | gymtrack       |
| Usuario  | gymtrack_user  |
| Password | gymtrack_pass  |
| Root PW  | root           |

---

## Stack tecnológico

| Capa       | Tecnología                          |
|------------|-------------------------------------|
| Frontend   | Vue 3 · Vite · Pinia · Vue Router · Axios |
| Backend    | PHP 8.2 · PDO · MVC · API REST      |
| Base de datos | MySQL 8.0                        |
| Entorno    | Docker · Docker Compose             |

---

## Comandos útiles

```bash
# Detener los contenedores
docker-compose down

# Ver logs del backend
docker-compose logs backend

# Ver logs de la base de datos
docker-compose logs db

# Acceder a MySQL desde la terminal
docker exec -it gymtrack_db mysql -u gymtrack_user -pgymtrack_pass gymtrack
```

---

*GymTrack · Proyecto Final · Tecnología Web Aplicada · 2026*

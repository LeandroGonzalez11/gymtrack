# GymTrack fusionado

Proyecto Vue 3 + Vite + PHP + MySQL + Docker, fusionado a partir de los ZIP entregados.

## Que incluye

- Landing premium con identidad GymTrack azul/carbon.
- Buscador demo de gimnasios por nombre, ciudad y categoria.
- Geolocalizacion con `navigator.geolocation` y mapa Leaflet/OpenStreetMap cuando hay conexion.
- Dashboard multi-rol:
  - Socio: gimnasios, reservas, membresias y progreso.
  - Gym Owner: metricas, ingresos, clases y accesos operativos.
  - Super Admin: gimnasios registrados, owners, socios, pagos e ingresos.
- Login, registro, auth, Docker y MySQL conservados desde la base funcional.
- Controladores extra de clases, reservas y membresias incorporados desde el segundo ZIP.

## Ejecutar

```bash
docker compose up --build
```

Luego abrir:

- Frontend: http://localhost:5173
- Backend: http://localhost:8080

Credenciales demo existentes:

- Email: `admin@gmail.com`
- Password: `admin123`

## Notas para la defensa

GymTrack esta planteado como plataforma SaaS multi-gimnasio. No representa un gimnasio unico:
cada gimnasio tiene owner propio, socios, clases, membresias y pagos internos. El super admin
administra la plataforma, no las clases del gimnasio.

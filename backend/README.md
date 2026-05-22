# GymTrack · Backend — ZIP 1: Autenticación

API REST desarrollada en PHP 8.2 siguiendo el patrón MVC.

## Estructura

```
backend/
├── index.php                   ← Punto de entrada único, headers CORS, autoload
├── config/
│   └── Database.php            ← Conexión PDO a MySQL (Singleton)
├── controllers/
│   ├── AuthController.php      ← Registro, login, logout
│   └── PerfilController.php    ← Ver y actualizar perfil
├── models/
│   └── Usuario.php             ← Operaciones sobre la tabla usuarios
├── middleware/
│   └── AuthMiddleware.php      ← Verificación de sesión y roles
└── routes/
    └── api.php                 ← Tabla de rutas de la API
```

## Endpoints disponibles en este ZIP

| Método | Ruta | Acceso | Descripción |
|--------|------|--------|-------------|
| POST | `/api/auth/registro` | Público | Registrar nuevo socio |
| POST | `/api/auth/login` | Público | Iniciar sesión |
| POST | `/api/auth/logout` | Público | Cerrar sesión |
| GET | `/api/perfil` | Autenticado | Ver perfil propio |
| PUT | `/api/perfil` | Autenticado | Actualizar nombre y teléfono |

## Ejemplos de uso

### Registro
```json
POST /api/auth/registro
{
  "nombre": "Leandro González",
  "email": "leandro@gmail.com",
  "password": "mipass123",
  "telefono": "099123456"
}
```

### Login
```json
POST /api/auth/login
{
  "email": "leandro@gmail.com",
  "password": "mipass123"
}
```
Respuesta exitosa incluye el `token` (session_id) que hay que enviar en las rutas protegidas:
```
Authorization: Bearer <token>
```

## Seguridad implementada
- **BCRYPT** con factor de coste 12 para contraseñas
- **PDO con prepared statements** en todas las consultas (anti SQL Injection)
- **htmlspecialchars()** en toda salida al JSON (anti XSS)
- **session_regenerate_id(true)** al hacer login (anti Session Fixation)
- Mismo mensaje de error para email inexistente y contraseña incorrecta (anti enumeración)

## ZIP 2 — próximo entregable
- Módulo de clases y reservas con transacciones atómicas
- Módulo de membresías
- Endpoints del panel de administración

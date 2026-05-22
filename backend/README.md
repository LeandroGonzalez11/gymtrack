# backend/

Esta carpeta contiene la API REST desarrollada en PHP 8.2 siguiendo el patrón MVC.

## Estructura prevista

```
backend/
├── index.php           ← Punto de entrada, headers CORS y router
├── config/
│   └── Database.php    ← Conexión PDO a MySQL
├── controllers/
│   ├── AuthController.php
│   ├── ClasesController.php
│   ├── ReservasController.php
│   └── MembresiaController.php
└── models/
    ├── Usuario.php
    ├── Membresia.php
    ├── Clase.php
    └── Reserva.php
```

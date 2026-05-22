<?php
/**
 * GymTrack · routes/api.php
 * ---------------------------------------------------------------
 * El router es el mapa de la API.
 * Lee la URL y el método HTTP de cada petición y decide
 * qué controlador y qué método ejecutar.
 *
 * Estructura de las rutas:
 *   MÉTODO  /api/recurso/accion  →  Controlador::metodo()
 *
 * Rutas disponibles en este ZIP (autenticación):
 *   POST  /api/auth/registro  →  AuthController::registrar()
 *   POST  /api/auth/login     →  AuthController::login()
 *   POST  /api/auth/logout    →  AuthController::logout()
 *   GET   /api/perfil         →  PerfilController::ver()      (protegida)
 *   PUT   /api/perfil         →  PerfilController::actualizar() (protegida)
 *
 * Las rutas de clases, reservas y membresías se agregarán en el ZIP 2.
 */

// ── Leer la URL y el método HTTP ──────────────────────────────
// REQUEST_URI tiene la URL completa, ej: /api/auth/login?foo=bar
// Quitamos los query params (?...) y normalizamos la ruta
$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri    = rtrim($uri, '/');          // quitamos la barra final si existe
$metodo = $_SERVER['REQUEST_METHOD']; // GET, POST, PUT, DELETE

// ── Tabla de rutas ────────────────────────────────────────────
// Formato: 'MÉTODO /ruta' => [Controlador, método]
$rutas = [
    // Autenticación — públicas (no requieren token)
    'POST /api/auth/registro' => ['AuthController', 'registrar'],
    'POST /api/auth/login'    => ['AuthController', 'login'],
    'POST /api/auth/logout'   => ['AuthController', 'logout'],

    // Perfil — protegidas (requieren sesión activa)
    'GET /api/perfil'         => ['PerfilController', 'ver'],
    'PUT /api/perfil'         => ['PerfilController', 'actualizar'],
];

// ── Resolver la ruta ──────────────────────────────────────────
$clave = "{$metodo} {$uri}";

if (array_key_exists($clave, $rutas)) {
    [$clase, $metodoAccion] = $rutas[$clave];

    // Verificamos que la clase exista (el autoload la buscará)
    if (class_exists($clase)) {
        $controlador = new $clase();
        $controlador->$metodoAccion();
    } else {
        // Esto no debería pasar en producción, pero ayuda en desarrollo
        http_response_code(500);
        echo json_encode([
            'error'   => true,
            'mensaje' => "Controlador '{$clase}' no encontrado."
        ]);
    }
} else {
    // La ruta no existe en nuestra API
    http_response_code(404);
    echo json_encode([
        'error'   => true,
        'mensaje' => "Ruta '{$uri}' no encontrada.",
        'metodo'  => $metodo
    ]);
}

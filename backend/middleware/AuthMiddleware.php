<?php
/**
 * GymTrack · AuthMiddleware.php (Middleware)
 * ---------------------------------------------------------------
 * ¿Qué es un Middleware?
 *   Es un "guardián" que se ejecuta ANTES del controlador.
 *   Su trabajo es verificar que la petición cumpla ciertas condiciones
 *   antes de permitir que llegue al controlador real.
 *
 * Este middleware verifica que el usuario esté autenticado.
 * Si no lo está, devuelve un error 401 y corta la ejecución.
 *
 * Uso típico en un controlador protegido:
 *   AuthMiddleware::verificarSesion();   // Si falla, para todo
 *   AuthMiddleware::verificarRol(2);     // Solo admins (rol_id = 2)
 */

class AuthMiddleware
{
    /**
     * Verifica que exista una sesión activa válida.
     *
     * El frontend envía el token (session_id) en el header Authorization:
     *   Authorization: Bearer <session_id>
     *
     * PHP lo verifica contra las sesiones activas del servidor.
     * Si no coincide o no existe, devuelve 401 Unauthorized.
     */
    public static function verificarSesion(): void
    {
        // Leemos el header Authorization de la petición
        $headerAuth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        // El formato esperado es "Bearer <token>"
        if (!str_starts_with($headerAuth, 'Bearer ')) {
            self::denegar(401, 'No autenticado. Iniciá sesión para continuar.');
        }

        // Extraemos el token quitando el prefijo "Bearer "
        $token = substr($headerAuth, 7);

        if (empty($token)) {
            self::denegar(401, 'Token de sesión inválido.');
        }

        // Iniciamos la sesión usando el ID recibido en el token
        session_id($token);
        session_start();

        // Si no hay usuario_id en la sesión, el token es inválido o expiró
        if (empty($_SESSION['usuario_id'])) {
            self::denegar(401, 'Sesión expirada. Iniciá sesión nuevamente.');
        }
    }

    /**
     * Verifica que el usuario autenticado tenga el rol requerido.
     *
     * Roles del sistema (tabla roles):
     *   1 = socio
     *   2 = admin
     *   3 = moderador
     *
     * @param int $rolRequerido  El rol_id mínimo necesario para acceder
     */
    public static function verificarRol(int $rolRequerido): void
    {
        // Este método siempre se llama después de verificarSesion(),
        // así que $_SESSION['rol_id'] ya está disponible
        $rolUsuario = (int) ($_SESSION['rol_id'] ?? 0);

        if ($rolUsuario !== $rolRequerido) {
            // 403 Forbidden: está autenticado pero no tiene permiso
            self::denegar(403, 'No tenés permisos para acceder a este recurso.');
        }
    }

    /**
     * Devuelve el ID del usuario autenticado en la sesión actual.
     * Útil en los controladores para saber quién está operando.
     *
     * @return int
     */
    public static function obtenerUsuarioId(): int
    {
        return (int) ($_SESSION['usuario_id'] ?? 0);
    }

    // ─────────────────────────────────────────────────────────
    // Helper privado: cortar ejecución con error JSON
    // ─────────────────────────────────────────────────────────

    /**
     * Envía una respuesta de error y termina la ejecución.
     * Ningún controlador protegido llega a ejecutarse si esto se dispara.
     */
    private static function denegar(int $codigo, string $mensaje): void
    {
        http_response_code($codigo);
        echo json_encode([
            'error'   => true,
            'mensaje' => $mensaje
        ], JSON_UNESCAPED_UNICODE);
        exit; // Cortamos todo — el controlador no se ejecuta
    }
}

<?php
/**
 * GymTrack · Database.php
 * ---------------------------------------------------------------
 * Clase responsable de crear y devolver la conexión a MySQL
 * usando PDO (PHP Data Objects).
 *
 * ¿Por qué PDO y no mysqli?
 *   PDO es más moderno, soporta múltiples motores de base de datos
 *   y fuerza el uso de prepared statements, que previenen SQL Injection.
 *
 * Patrón usado: Singleton.
 *   Solo se crea UNA conexión por petición HTTP. Si algún controlador
 *   llama a Database::conectar() varias veces, siempre recibe la misma
 *   instancia en lugar de abrir nuevas conexiones innecesarias.
 */

class Database
{
    // ── Credenciales de conexión ──────────────────────────────
    // Estas variables se leen desde el entorno Docker definido
    // en docker-compose.yml. Si no existen, usan el valor por defecto.
    private static string $host     = 'db';             // nombre del servicio en docker-compose
    private static string $puerto   = '3306';
    private static string $baseDatos = 'gymtrack';
    private static string $usuario  = 'gymtrack_user';
    private static string $password = 'gymtrack_pass';

    // La única instancia activa de PDO (patrón Singleton)
    private static ?PDO $instancia = null;

    /**
     * Devuelve la conexión PDO activa.
     * Si todavía no existe, la crea.
     *
     * Uso desde cualquier controlador:
     *   $pdo = Database::conectar();
     */
    public static function conectar(): PDO
    {
        // Si ya hay una conexión activa, la devolvemos directamente
        if (self::$instancia !== null) {
            return self::$instancia;
        }

        try {
            // DSN = Data Source Name. Le dice a PDO cómo conectarse.
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                self::$host,
                self::$puerto,
                self::$baseDatos
            );

            self::$instancia = new PDO($dsn, self::$usuario, self::$password, [
                // Si una consulta falla, PDO lanza una excepción en lugar
                // de devolver false silenciosamente (más fácil de depurar)
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

                // Los resultados llegan como arrays asociativos por defecto
                // Ejemplo: $fila['email'] en lugar de $fila[1]
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

                // Desactivamos el emulado de prepared statements.
                // Así MySQL ejecuta los prepared statements de verdad,
                // lo que mejora la seguridad contra SQL Injection.
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            return self::$instancia;

        } catch (PDOException $e) {
            // En producción NUNCA mostramos el mensaje real del error
            // porque podría revelar la estructura de la base de datos.
            http_response_code(500);
            echo json_encode([
                'error'   => true,
                'mensaje' => 'Error de conexión a la base de datos.'
            ]);
            exit;
        }
    }

    // Constructor privado: impide crear instancias con "new Database()"
    private function __construct() {}
}

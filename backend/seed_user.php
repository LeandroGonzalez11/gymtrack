<?php
/**
 * GymTrack · seed_user.php
 * ---------------------------------------------------------------
 * Crea un usuario común de ejemplo para pruebas.
 * Ejecutar desde backend/: php seed_user.php
 */

require_once __DIR__ . '/config/Database.php';

try {
    $pdo = Database::conectar();

    $email = 'usuario@gmail.com';
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);

    if ((int) $stmt->fetchColumn() > 0) {
        echo "La cuenta de usuario ya existe.\n";
        exit(0);
    }

    $passwordHash = password_hash('usuario123', PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nombre, email, password_hash, telefono, rol_id, activo)
         VALUES (?, ?, ?, NULL, 1, 1)'
    );
    $stmt->execute(['Usuario Demo', $email, $passwordHash]);

    echo "Cuenta de usuario creada correctamente. Email: {$email}, Password: usuario123\n";
} catch (PDOException $e) {
    echo "Error al crear cuenta de usuario: " . $e->getMessage() . "\n";
    exit(1);
}

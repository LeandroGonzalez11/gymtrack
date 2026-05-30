<?php
/**
 * GymTrack · seed_admin.php
 * ---------------------------------------------------------------
 * Script útil para crear la cuenta admin si la base de datos ya está
 * inicializada pero no tiene la fila de administrador.
 *
 * Ejecutar desde backend/: php seed_admin.php
 */

require_once __DIR__ . '/config/Database.php';

try {
    $pdo = Database::conectar();

    $email = 'admin@gmail.com';
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);

    if ((int) $stmt->fetchColumn() > 0) {
        echo "La cuenta admin ya existe.\n";
        exit(0);
    }

    $passwordHash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nombre, email, password_hash, telefono, rol_id, activo)
         VALUES (?, ?, ?, NULL, 2, 1)'
    );
    $stmt->execute(['Administrador GymTrack', $email, $passwordHash]);

    echo "Cuenta admin creada correctamente. Email: {$email}, Password: admin123\n";
} catch (PDOException $e) {
    echo "Error al crear cuenta admin: " . $e->getMessage() . "\n";
    exit(1);
}

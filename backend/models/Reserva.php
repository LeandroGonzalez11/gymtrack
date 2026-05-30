<?php
/**
 * GymTrack · Reserva.php
 * ---------------------------------------------------------------
 * Modelo para la tabla reservas.
 */

class Reserva
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function listarPorRango(string $desde, string $hasta): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.id, r.usuario_id, u.nombre AS usuario_nombre, u.email AS usuario_email,
                    r.clase_id, c.nombre AS clase_nombre, c.dia_semana, c.hora_inicio, c.hora_fin,
                    r.fecha_reserva, r.estado
             FROM reservas r
             JOIN usuarios u ON r.usuario_id = u.id
             JOIN clases c ON r.clase_id = c.id
             WHERE r.fecha_reserva BETWEEN ? AND ?
             ORDER BY r.fecha_reserva DESC'
        );

        $stmt->execute([$desde, $hasta]);

        return $stmt->fetchAll();
    }

    public function cancelar(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE reservas SET estado = "cancelada" WHERE id = ?'
        );

        return $stmt->execute([$id]);
    }
}

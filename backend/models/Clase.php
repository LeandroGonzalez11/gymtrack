<?php
/**
 * GymTrack · Clase.php
 * ---------------------------------------------------------------
 * Modelo para la tabla clases.
 */

class Clase
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function listarTodos(): array
    {
        $stmt = $this->pdo->query(
            'SELECT c.id, c.nombre, c.instructor_id, u.nombre AS instructor_nombre,
                    c.dia_semana, c.hora_inicio, c.hora_fin, c.cupo_maximo,
                    c.cupos_disponibles, c.activa
             FROM clases c
             JOIN usuarios u ON c.instructor_id = u.id
             ORDER BY FIELD(c.dia_semana, "lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"), c.hora_inicio'
        );

        return $stmt->fetchAll();
    }

    public function crear(string $nombre, int $instructorId, string $diaSemana, string $horaInicio, string $horaFin, int $cupoMaximo): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO clases (nombre, instructor_id, dia_semana, hora_inicio, hora_fin, cupo_maximo, cupos_disponibles)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([$nombre, $instructorId, $diaSemana, $horaInicio, $horaFin, $cupoMaximo, $cupoMaximo]);

        return (int) $this->pdo->lastInsertId();
    }

    public function actualizar(int $id, string $nombre, int $instructorId, string $diaSemana, string $horaInicio, string $horaFin, int $cupoMaximo): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE clases
             SET nombre = ?, instructor_id = ?, dia_semana = ?, hora_inicio = ?, hora_fin = ?, cupo_maximo = ?, cupos_disponibles = ?
             WHERE id = ?'
        );

        return $stmt->execute([$nombre, $instructorId, $diaSemana, $horaInicio, $horaFin, $cupoMaximo, $cupoMaximo, $id]);
    }

    public function cancelar(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE clases SET activa = 0 WHERE id = ?'
        );

        return $stmt->execute([$id]);
    }

    public function buscarPorId(int $id): array|false
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nombre, instructor_id, dia_semana, hora_inicio, hora_fin, cupo_maximo, cupos_disponibles, activa
             FROM clases WHERE id = ? LIMIT 1'
        );
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    public function listarInscriptos(int $claseId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT r.id AS reserva_id, u.id AS usuario_id, u.nombre AS usuario_nombre, u.email AS usuario_email,
                    r.fecha_reserva, r.estado
             FROM reservas r
             JOIN usuarios u ON r.usuario_id = u.id
             WHERE r.clase_id = ?
             ORDER BY r.fecha_reserva DESC'
        );

        $stmt->execute([$claseId]);

        return $stmt->fetchAll();
    }

    public function contarClasesHoy(): int
    {
        $dias = [1 => 'lunes', 2 => 'martes', 3 => 'miercoles', 4 => 'jueves', 5 => 'viernes', 6 => 'sabado', 7 => 'domingo'];
        $diaHoy = $dias[(int) date('N')];

        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM clases WHERE dia_semana = ? AND activa = 1'
        );
        $stmt->execute([$diaHoy]);

        return (int) $stmt->fetchColumn();
    }
}

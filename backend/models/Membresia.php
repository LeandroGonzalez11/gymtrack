<?php
/**
 * GymTrack · Membresia.php
 * ---------------------------------------------------------------
 * Modelo para la tabla membresias.
 */

class Membresia
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::conectar();
    }

    public function listarTodos(): array
    {
        $stmt = $this->pdo->query(
            'SELECT m.id, m.usuario_id, u.nombre AS usuario_nombre, u.email AS usuario_email,
                    m.plan, m.fecha_inicio, m.fecha_vencimiento, m.estado, m.precio_pagado, m.creado_en
             FROM membresias m
             JOIN usuarios u ON m.usuario_id = u.id
             ORDER BY m.fecha_vencimiento ASC, m.creado_en DESC'
        );

        return $stmt->fetchAll();
    }

    public function crear(int $usuarioId, string $plan, string $fechaInicio, string $fechaVencimiento, float $precioPagado): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO membresias (usuario_id, plan, fecha_inicio, fecha_vencimiento, precio_pagado)
             VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([$usuarioId, $plan, $fechaInicio, $fechaVencimiento, $precioPagado]);

        return (int) $this->pdo->lastInsertId();
    }

    public function listarVencidas(): array
    {
        $stmt = $this->pdo->query(
            'SELECT m.id, m.usuario_id, u.nombre AS usuario_nombre, u.email AS usuario_email,
                    m.plan, m.fecha_inicio, m.fecha_vencimiento, m.estado, m.precio_pagado
             FROM membresias m
             JOIN usuarios u ON m.usuario_id = u.id
             WHERE m.estado = "vencida" OR m.fecha_vencimiento < CURRENT_DATE()
             ORDER BY m.fecha_vencimiento ASC'
        );

        return $stmt->fetchAll();
    }

    public function contarPorVencer(int $dias = 7): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(DISTINCT m.usuario_id)
             FROM membresias m
             WHERE m.estado = "activa"
               AND m.fecha_vencimiento BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL ? DAY)'
        );

        $stmt->execute([$dias]);

        return (int) $stmt->fetchColumn();
    }

    public function listarPorUsuario(int $usuarioId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, plan, fecha_inicio, fecha_vencimiento, estado, precio_pagado, creado_en
             FROM membresias
             WHERE usuario_id = ?
             ORDER BY fecha_vencimiento DESC'
        );

        $stmt->execute([$usuarioId]);

        return $stmt->fetchAll();
    }
}

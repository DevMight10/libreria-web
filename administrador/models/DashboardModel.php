<?php
class DashboardModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getStats() {
        $stats = [];
        $stats['total_libros'] = $this->pdo->query("SELECT COUNT(*) FROM libros WHERE activo = 1")->fetchColumn();
        $stats['pedidos_pendientes'] = $this->pdo->query("SELECT COUNT(*) FROM pedidos WHERE estado = 'pendiente'")->fetchColumn();
        $stats['mensajes_nuevos'] = $this->pdo->query("SELECT COUNT(*) FROM mensajes WHERE leido = 0")->fetchColumn();
        $stats['total_generos'] = $this->pdo->query("SELECT COUNT(*) FROM generos")->fetchColumn();
        $stats['total_usuarios'] = $this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        return $stats;
    }

    public function getRecentOrders($limit = 5) {
        $stmt = $this->pdo->query("
            SELECT p.id, p.numero_pedido, p.fecha_pedido, p.total, p.estado, u.nombre as cliente
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY p.fecha_pedido DESC
            LIMIT " . (int)$limit
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalesData($days = 7) {
        $stmt = $this->pdo->query("
            SELECT DATE(fecha_pedido) AS dia, SUM(total) AS total_dia
            FROM pedidos
            WHERE fecha_pedido >= DATE_SUB(CURDATE(), INTERVAL " . ((int)$days - 1) . " DAY)
            GROUP BY DATE(fecha_pedido)
            ORDER BY DATE(fecha_pedido) ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
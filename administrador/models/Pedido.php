<?php
class PedidoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function applyFilters($sql, $filtros) {
        $where_conditions = [];
        $params = [];

        if (!empty($filtros['estado']) && $filtros['estado'] !== 'todos') {
            $where_conditions[] = 'p.estado = ?';
            $params[] = $filtros['estado'];
        }

        if (!empty($filtros['codigo'])) {
            $where_conditions[] = 'p.numero_pedido LIKE ?';
            $params[] = "%{$filtros['codigo']}";
        }

        if (!empty($where_conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $where_conditions);
        }

        return ['sql' => $sql, 'params' => $params];
    }

    public function countAll($filtros = []) {
        $base_sql = "SELECT COUNT(*) FROM pedidos p";
        $filtered_sql = $this->applyFilters($base_sql, $filtros);
        
        $stmt = $this->pdo->prepare($filtered_sql['sql']);
        $stmt->execute($filtered_sql['params']);
        return $stmt->fetchColumn();
    }

    public function getAll($filtros = [], $offset = 0, $limit = 10) {
        $base_sql = "
            SELECT 
                p.*,
                u.nombre as cliente_nombre, u.email as cliente_email
            FROM pedidos p
            JOIN usuarios u ON p.usuario_id = u.id
        ";
        $filtered_sql = $this->applyFilters($base_sql, $filtros);
        
        $final_sql = $filtered_sql['sql'] . " ORDER BY p.fecha_pedido DESC LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = $this->pdo->prepare($final_sql);
        $stmt->execute($filtered_sql['params']);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener detalles para cada pedido
        foreach ($pedidos as &$pedido) {
            $pedido['libros'] = $this->getDetails($pedido['id']);
        }

        return $pedidos;
    }

    public function getDetails($pedido_id) {
        $stmt = $this->pdo->prepare("
            SELECT pd.*, l.nombre as libro_nombre, l.imagen as libro_imagen
            FROM pedido_detalles pd
            JOIN libros l ON pd.libro_id = l.id
            WHERE pd.pedido_id = ?
        ");
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateStatus($pedido_id, $nuevo_estado) {
        // Opcional: Añadir lógica de validación de transición de estado aquí
        $stmt = $this->pdo->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
        return $stmt->execute([$nuevo_estado, $pedido_id]);
    }
}
?>
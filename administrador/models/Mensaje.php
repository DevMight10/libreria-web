<?php
class MensajeModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    private function applyFilters($sql, $filtros) {
        $where_conditions = [];
        $params = [];

        if (!empty($filtros['estado'])) {
            if ($filtros['estado'] === 'nuevos') {
                $where_conditions[] = 'leido = 0';
            } elseif ($filtros['estado'] === 'leidos') {
                $where_conditions[] = 'leido = 1';
            }
        }

        if (!empty($filtros['buscar'])) {
            $where_conditions[] = '(nombre LIKE ? OR email LIKE ? OR asunto LIKE ? OR mensaje LIKE ?)';
            for ($i = 0; $i < 4; $i++) {
                $params[] = "%{$filtros['buscar']}";
            }
        }

        if (!empty($where_conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $where_conditions);
        }

        return ['sql' => $sql, 'params' => $params];
    }

    public function countAll($filtros = []) {
        $base_sql = "SELECT COUNT(*) FROM mensajes";
        $filtered_sql = $this->applyFilters($base_sql, $filtros);
        
        $stmt = $this->pdo->prepare($filtered_sql['sql']);
        $stmt->execute($filtered_sql['params']);
        return $stmt->fetchColumn();
    }

    public function getAll($filtros = [], $offset = 0, $limit = 15) {
        $base_sql = "SELECT * FROM mensajes";
        $filtered_sql = $this->applyFilters($base_sql, $filtros);
        
        $final_sql = $filtered_sql['sql'] . " ORDER BY fecha_envio DESC LIMIT {$limit} OFFSET {$offset}";
        
        $stmt = $this->pdo->prepare($final_sql);
        $stmt->execute($filtered_sql['params']);
        return $stmt->fetchAll();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM mensajes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function markAsRead($id) {
        $stmt = $this->pdo->prepare("UPDATE mensajes SET leido = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>

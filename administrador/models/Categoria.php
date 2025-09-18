<?php
class CategoriaModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT c.*, COUNT(p.id) as product_count
            FROM categorias c
            LEFT JOIN productos p ON c.id = p.categoria_id
            GROUP BY c.id
            ORDER BY c.id ASC
        ");
        return $stmt->fetchAll();
    }

    public function add($nombre) {
        if (empty($nombre)) {
            return false;
        }
        $stmt = $this->pdo->prepare("INSERT INTO categorias (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }

    public function isUsed($id) {
        $stmt_check = $this->pdo->prepare("SELECT COUNT(*) FROM productos WHERE categoria_id = ?");
        $stmt_check->execute([$id]);
        return $stmt_check->fetchColumn() > 0;
    }

    public function delete($id) {
        if ($this->isUsed($id)) {
            return false; // No se puede eliminar si está en uso
        }
        $stmt = $this->pdo->prepare("DELETE FROM categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM categorias WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $nombre) {
        if (empty($nombre)) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE categorias SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }
}
?>

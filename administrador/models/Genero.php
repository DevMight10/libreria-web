<?php
class GeneroModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT g.*, COUNT(l.id) as libro_count
            FROM generos g
            LEFT JOIN libros l ON g.id = l.genero_id
            GROUP BY g.id
            ORDER BY g.id ASC
        ");
        return $stmt->fetchAll();
    }

    public function add($nombre) {
        if (empty($nombre)) {
            return false;
        }
        $stmt = $this->pdo->prepare("INSERT INTO generos (nombre) VALUES (?)");
        return $stmt->execute([$nombre]);
    }

    public function isUsed($id) {
        $stmt_check = $this->pdo->prepare("SELECT COUNT(*) FROM libros WHERE genero_id = ?");
        $stmt_check->execute([$id]);
        return $stmt_check->fetchColumn() > 0;
    }

    public function delete($id) {
        if ($this->isUsed($id)) {
            return false; // No se puede eliminar si está en uso
        }
        $stmt = $this->pdo->prepare("DELETE FROM generos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM generos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $nombre) {
        if (empty($nombre)) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE generos SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }
}
?>
<?php
class UsuarioModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT id, nombre, email, tipo, fecha_registro 
            FROM usuarios 
            ORDER BY fecha_registro DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT id, nombre, email, tipo FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateType($id, $tipo) {
        if (!in_array($tipo, ['cliente', 'admin'])) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE usuarios SET tipo = ? WHERE id = ?");
        return $stmt->execute([$tipo, $id]);
    }

    public function update($id, $data) {
        if (empty($data['nombre']) || !in_array($data['tipo'], ['cliente', 'admin'])) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ?, tipo = ? WHERE id = ?");
        return $stmt->execute([$data['nombre'], $data['tipo'], $id]);
    }

    public function delete($id) {
        // Opcional: Añadir lógica para no poder eliminar el último admin o a uno mismo.
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>

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
        $stmt = $this->pdo->prepare("SELECT id, nombre, email, tipo, foto_perfil, password_actualizado_en FROM usuarios WHERE id = ?");
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

    public function updateProfileInfo($id, $nombre) {
        if (empty($nombre)) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
        return $stmt->execute([$nombre, $id]);
    }

    public function updateProfilePicture($id, $path) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        return $stmt->execute([$path, $id]);
    }

    public function delete($id) {
        // Opcional: Añadir lógica para no poder eliminar el último admin o a uno mismo.
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPasswordHash($id) {
        $stmt = $this->pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }

    public function changePassword($id, $new_password_hash) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET password = ?, password_actualizado_en = NOW() WHERE id = ?");
        return $stmt->execute([$new_password_hash, $id]);
    }
}
?>

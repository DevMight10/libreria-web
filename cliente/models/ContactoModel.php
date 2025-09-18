<?php
class ContactoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function guardarMensaje($data) {
        if (empty($data['nombre']) || empty($data['email']) || empty($data['mensaje'])) {
            return false;
        }

        $sql = "INSERT INTO mensajes (nombre, email, asunto, mensaje) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $data['asunto'],
            $data['mensaje']
        ]);
    }
}
?>

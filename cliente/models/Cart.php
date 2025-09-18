<?php
class Cart {
    private $pdo;
    private $user_id;

    public function __construct($pdo, $user_id) {
        $this->pdo = $pdo;
        $this->user_id = $user_id;
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    public function add($producto_id, $nombre, $precio, $imagen, $cantidad = 1) {
        if (isset($_SESSION['carrito'][$producto_id])) {
            $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$producto_id] = [
                'nombre' => $nombre,
                'precio' => $precio,
                'imagen' => $imagen,
                'cantidad' => $cantidad
            ];
        }
        $this->syncItem($producto_id);
    }

    public function update($producto_id, $cantidad) {
        if (isset($_SESSION['carrito'][$producto_id])) {
            if ($cantidad > 0) {
                $_SESSION['carrito'][$producto_id]['cantidad'] = $cantidad;
                $this->syncItem($producto_id);
            } else {
                $this->remove($producto_id);
            }
        }
    }

    public function remove($producto_id) {
        unset($_SESSION['carrito'][$producto_id]);
        $stmt = $this->pdo->prepare("DELETE FROM carrito_items WHERE usuario_id = ? AND producto_id = ?");
        $stmt->execute([$this->user_id, $producto_id]);
    }

    public function clear() {
        $_SESSION['carrito'] = [];
        $stmt = $this->pdo->prepare("DELETE FROM carrito_items WHERE usuario_id = ?");
        $stmt->execute([$this->user_id]);
    }

    public function getItems() {
        return $_SESSION['carrito'] ?? [];
    }

    public function getTotal() {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        return $total;
    }

    private function syncItem($producto_id) {
        $cantidad = $_SESSION['carrito'][$producto_id]['cantidad'];
        $sql = "INSERT INTO carrito_items (usuario_id, producto_id, cantidad) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE cantidad = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->user_id, $producto_id, $cantidad, $cantidad]);
    }
}
?>

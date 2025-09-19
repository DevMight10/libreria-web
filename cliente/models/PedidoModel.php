<?php
class PedidoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function verificarStock($cart_items) {
        $errores = [];
        foreach ($cart_items as $libro_id => $item) {
            $stmt = $this->pdo->prepare("SELECT nombre, stock FROM libros WHERE id = ?");
            $stmt->execute([$libro_id]);
            $libro_db = $stmt->fetch();

            if (!$libro_db || $item['cantidad'] > $libro_db['stock']) {
                $errores[] = "No hay suficiente stock para \"{$item['nombre']}\". Disponibles: " . ($libro_db['stock'] ?? 0) . ".";
            }
        }
        return $errores;
    }

    public function crearPedido($user_id, $cart_items, $total) {
        try {
            $this->pdo->beginTransaction();
            
            $numero_pedido = 'LA-' . date('Ymd') . rand(1000, 9999); // LA por Librería Adrimarth
            
            $stmt = $this->pdo->prepare("INSERT INTO pedidos (usuario_id, numero_pedido, total) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $numero_pedido, $total]);
            $pedido_id = $this->pdo->lastInsertId();
            
            foreach ($cart_items as $libro_id => $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $stmt_detalle = $this->pdo->prepare(
                    "INSERT INTO pedido_detalles (pedido_id, libro_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)"
                );
                $stmt_detalle->execute([$pedido_id, $libro_id, $item['cantidad'], $item['precio'], $subtotal]);

                $stmt_stock_update = $this->pdo->prepare("UPDATE libros SET stock = stock - ? WHERE id = ?");
                $stmt_stock_update->execute([$item['cantidad'], $libro_id]);
            }
            
            $this->pdo->commit();
            
            return $numero_pedido;

        } catch (Exception $e) {
            $this->pdo->rollback();
            return false;
        }
    }

    public function getPedidosPorUsuario($user_id) {
        $sql = "
            SELECT 
                p.id as pedido_id, p.numero_pedido, p.fecha_pedido, p.total, p.estado,
                pd.cantidad, pd.precio_unitario,
                l.nombre as libro_nombre, l.imagen as libro_imagen
            FROM pedidos p
            JOIN pedido_detalles pd ON p.id = pd.pedido_id
            JOIN libros l ON pd.libro_id = l.id
            WHERE p.usuario_id = ?
            ORDER BY p.fecha_pedido DESC, p.id ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pedidos = [];
        foreach ($results as $row) {
            $pedido_id = $row['pedido_id'];
            if (!isset($pedidos[$pedido_id])) {
                $pedidos[$pedido_id] = [
                    'numero_pedido' => $row['numero_pedido'],
                    'fecha_pedido' => $row['fecha_pedido'],
                    'total' => $row['total'],
                    'estado' => $row['estado'],
                    'libros' => []
                ];
            }
            $pedidos[$pedido_id]['libros'][] = [
                'nombre' => $row['libro_nombre'],
                'imagen' => $row['libro_imagen'],
                'cantidad' => $row['cantidad'],
                'precio_unitario' => $row['precio_unitario']
            ];
        }
        return $pedidos;
    }

    public function cancelarPedido($pedido_id, $user_id) {
        try {
            $this->pdo->beginTransaction();

            $stmt_check = $this->pdo->prepare("SELECT id FROM pedidos WHERE id = ? AND usuario_id = ? AND estado = 'pendiente'");
            $stmt_check->execute([$pedido_id, $user_id]);
            
            if (!$stmt_check->fetch()) {
                throw new Exception("No se puede cancelar este pedido.");
            }

            $stmt_detalles = $this->pdo->prepare("SELECT libro_id, cantidad FROM pedido_detalles WHERE pedido_id = ?");
            $stmt_detalles->execute([$pedido_id]);
            $detalles = $stmt_detalles->fetchAll();

            foreach ($detalles as $detalle) {
                $stmt_stock = $this->pdo->prepare("UPDATE libros SET stock = stock + ? WHERE id = ?");
                $stmt_stock->execute([$detalle['cantidad'], $detalle['libro_id']]);
            }

            $stmt_cancel = $this->pdo->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id = ?");
            $stmt_cancel->execute([$pedido_id]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
?>
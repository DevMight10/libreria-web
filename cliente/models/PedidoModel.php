<?php
class PedidoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function verificarStock($cart_items) {
        $errores = [];
        foreach ($cart_items as $producto_id => $item) {
            $stmt = $this->pdo->prepare("SELECT nombre, stock FROM productos WHERE id = ?");
            $stmt->execute([$producto_id]);
            $producto_db = $stmt->fetch();

            if (!$producto_db || $item['cantidad'] > $producto_db['stock']) {
                $errores[] = "No hay suficiente stock para \"{$item['nombre']}\". Disponibles: " . ($producto_db['stock'] ?? 0) . ".";
            }
        }
        return $errores;
    }

    public function crearPedido($user_id, $cart_items, $total) {
        try {
            $this->pdo->beginTransaction();
            
            $numero_pedido = 'MC-' . date('Ymd') . rand(1000, 9999);
            
            $stmt = $this->pdo->prepare("INSERT INTO pedidos (usuario_id, numero_pedido, total) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $numero_pedido, $total]);
            $pedido_id = $this->pdo->lastInsertId();
            
            foreach ($cart_items as $producto_id => $item) {
                $subtotal = $item['precio'] * $item['cantidad'];
                $stmt_detalle = $this->pdo->prepare(
                    "INSERT INTO pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)"
                );
                $stmt_detalle->execute([$pedido_id, $producto_id, $item['cantidad'], $item['precio'], $subtotal]);

                $stmt_stock_update = $this->pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
                $stmt_stock_update->execute([$item['cantidad'], $producto_id]);
            }
            
            $this->pdo->commit();
            
            return $numero_pedido; // Devolver el número de pedido en caso de éxito

        } catch (Exception $e) {
            $this->pdo->rollback();
            // Opcional: loggear el error $e->getMessage()
            return false;
        }
    }

    public function getPedidosPorUsuario($user_id) {
        $sql = "
            SELECT 
                p.id as pedido_id, p.numero_pedido, p.fecha_pedido, p.total, p.estado,
                pd.cantidad, pd.precio_unitario,
                pr.nombre as producto_nombre, pr.imagen as producto_imagen
            FROM pedidos p
            JOIN pedido_detalles pd ON p.id = pd.pedido_id
            JOIN productos pr ON pd.producto_id = pr.id
            WHERE p.usuario_id = ?
            ORDER BY p.fecha_pedido DESC, p.id ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar productos por pedido
        $pedidos = [];
        foreach ($results as $row) {
            $pedido_id = $row['pedido_id'];
            if (!isset($pedidos[$pedido_id])) {
                $pedidos[$pedido_id] = [
                    'numero_pedido' => $row['numero_pedido'],
                    'fecha_pedido' => $row['fecha_pedido'],
                    'total' => $row['total'],
                    'estado' => $row['estado'],
                    'productos' => []
                ];
            }
            $pedidos[$pedido_id]['productos'][] = [
                'nombre' => $row['producto_nombre'],
                'imagen' => $row['producto_imagen'],
                'cantidad' => $row['cantidad'],
                'precio_unitario' => $row['precio_unitario']
            ];
        }
        return $pedidos;
    }

    public function cancelarPedido($pedido_id, $user_id) {
        try {
            $this->pdo->beginTransaction();

            // 1. Verificar que el pedido pertenece al usuario y está 'pendiente'
            $stmt_check = $this->pdo->prepare("SELECT id FROM pedidos WHERE id = ? AND usuario_id = ? AND estado = 'pendiente'");
            $stmt_check->execute([$pedido_id, $user_id]);
            
            if (!$stmt_check->fetch()) {
                throw new Exception("No se puede cancelar este pedido.");
            }

            // 2. Obtener los detalles del pedido para reponer stock
            $stmt_detalles = $this->pdo->prepare("SELECT producto_id, cantidad FROM pedido_detalles WHERE pedido_id = ?");
            $stmt_detalles->execute([$pedido_id]);
            $detalles = $stmt_detalles->fetchAll();

            // 3. Reponer el stock para cada producto
            foreach ($detalles as $detalle) {
                $stmt_stock = $this->pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
                $stmt_stock->execute([$detalle['cantidad'], $detalle['producto_id']]);
            }

            // 4. Actualizar el estado del pedido a 'cancelado'
            $stmt_cancel = $this->pdo->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id = ?");
            $stmt_cancel->execute([$pedido_id]);

            $this->pdo->commit();
            return true;

        } catch (Exception $e) {
            $this->pdo->rollBack();
            // Devolver el mensaje de error para que el controlador lo maneje
            throw $e;
        }
    }
}
?>

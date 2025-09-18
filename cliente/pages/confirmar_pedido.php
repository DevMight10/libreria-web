<?php
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';

requireLogin();

$page_title = 'Confirmar Pedido';

if (empty($_SESSION['carrito'])) {
    header('Location: /proyecto-01/cliente/pages/productos.php');
    exit();
}

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_pedido'])) {
    
    // 1. Pre-confirmación de stock
    $errores_stock = [];
    foreach ($_SESSION['carrito'] as $producto_id => $item) {
        $stmt_stock = $pdo->prepare("SELECT nombre, stock FROM productos WHERE id = ?");
        $stmt_stock->execute([$producto_id]);
        $producto_db = $stmt_stock->fetch();

        if (!$producto_db || $item['cantidad'] > $producto_db['stock']) {
            $errores_stock[] = "No hay suficiente stock para \"{$item['nombre']}\". Disponibles: " . ($producto_db['stock'] ?? 0) . ".";
        }
    }

    if (!empty($errores_stock)) {
        $_SESSION['mensaje_error'] = implode('<br>', $errores_stock);
        header('Location: /proyecto-01/cliente/pages/carrito.php');
        exit;
    }

    // 2. Procesar el pedido si hay stock
    try {
        $pdo->beginTransaction();
        
        // Crear pedido
        $numero_pedido = generateOrderNumber();
        $total = getCartTotal();
        $usuario_id = $_SESSION['usuario_id'];
        
        $stmt = $pdo->prepare("INSERT INTO pedidos (usuario_id, numero_pedido, total) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $numero_pedido, $total]);
        $pedido_id = $pdo->lastInsertId();
        
        // Agregar detalles del pedido y actualizar stock
        foreach ($_SESSION['carrito'] as $producto_id => $item) {
            // Insertar detalle
            $subtotal = $item['precio'] * $item['cantidad'];
            $stmt_detalle = $pdo->prepare("INSERT INTO pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmt_detalle->execute([$pedido_id, $producto_id, $item['cantidad'], $item['precio'], $subtotal]);

            // Actualizar stock
            $stmt_stock_update = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
            $stmt_stock_update->execute([$item['cantidad'], $producto_id]);
        }
        
        $pdo->commit();
        
        // Limpiar carrito de la sesión
        unset($_SESSION['carrito']);

        // Limpiar carrito de la base de datos
        $stmt_clear = $pdo->prepare("DELETE FROM carrito_items WHERE usuario_id = ?");
        $stmt_clear->execute([$usuario_id]);
        
        $mensaje = "Pedido confirmado exitosamente. Número de pedido: $numero_pedido";
        
    } catch (Exception $e) {
        $pdo->rollback();
        $mensaje = "Error al procesar el pedido. Inténtalo nuevamente.";
        // Opcional: loggear el error $e->getMessage()
    }
}

include '../../public/componentes/header.php';
?>
<link rel="stylesheet" href="/proyecto-01/cliente/styles/confirmar_pedido.css">

<main>
    <div class="container">
        <h1>Confirmar Pedido</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert <?php echo strpos($mensaje, 'exitosamente') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $mensaje; ?>
            </div>
            
            <?php if (strpos($mensaje, 'exitosamente') !== false): ?>
                <div class="order-success">
                    <h2>¡Gracias por tu pedido!</h2>
                    <p>Nos pondremos en contacto contigo pronto para coordinar el pago y la entrega.</p>
                    <a href="/proyecto-01/cliente/pages/productos.php" class="btn btn-primary">Continuar Comprando</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="order-summary">
                <h2>Resumen del Pedido</h2>
                
                <div class="order-items">
                    <?php foreach ($_SESSION['carrito'] as $producto_id => $item): ?>
                        <div class="order-item">
                            <img src="/proyecto-01/public/<?php echo $item['imagen']; ?>" 
                                 alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                                <p>Cantidad: <?php echo $item['cantidad']; ?></p>
                                <p>Precio: <?php echo formatPrice($item['precio']); ?></p>
                            </div>
                            <div class="item-total">
                                <?php echo formatPrice($item['precio'] * $item['cantidad']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    <h3>Total: <?php echo formatPrice(getCartTotal()); ?></h3>
                </div>
                
                <div class="order-info">
                    <h3>Información Importante</h3>
                    <ul>
                        <li>Tu pedido será procesado y nos pondremos en contacto contigo</li>
                        <li>Coordinaremos el método de pago y entrega</li>
                        <li>El estado de tu pedido será actualizado en nuestro sistema</li>
                    </ul>
                </div>
                
                <form method="POST" class="confirm-form">
                    <button type="submit" name="confirmar_pedido" class="btn btn-primary">
                        Confirmar Pedido
                    </button>
                    <a href="/proyecto-01/cliente/pages/carrito.php" class="btn btn-secondary">Volver al Carrito</a>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../public/componentes/footer.php'; ?>

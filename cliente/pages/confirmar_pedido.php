<?php
// 1. Incluir el controlador
require_once '../controllers/confirmar_pedido_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/cliente/styles/confirmar_pedido.css">

<!-- 4. Contenido HTML -->
<main>
    <div class="container">
        <h1><?php echo $page_title; ?></h1>
        
        <?php if ($mensaje): ?>
            <div class="alert <?php echo strpos($mensaje, 'exitosamente') !== false ? 'alert-success' : 'alert-error'; ?>">
                <?php echo $mensaje; ?>
            </div>
            
            <?php if (strpos($mensaje, 'exitosamente') !== false): ?>
                <div class="order-success">
                    <h2>¡Gracias por tu pedido!</h2>
                    <p>Nos pondremos en contacto contigo pronto para coordinar el pago y la entrega.</p>
                    <a href="/proyecto-01/cliente/pages/libros.php" class="btn btn-primary">Seguir Viendo Libros</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="order-summary">
                <h2>Resumen del Pedido</h2>
                
                <div class="order-items">
                    <?php foreach ($carrito_items as $libro_id => $item): ?>
                        <div class="order-item">
                            <img src="/proyecto-01/public/<?php echo htmlspecialchars($item['imagen']); ?>" 
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
                    <h3>Total: <?php echo formatPrice($cart->getTotal()); ?></h3>
                </div>
                
                <div class="order-info">
                    <h3>Información Importante</h3>
                    <ul>
                        <li>Tu pedido será procesado y nos pondremos en contacto contigo</li>
                        <li>Coordinaremos el método de pago y entrega</li>
                        <li>El estado de tu pedido será actualizado en nuestro sistema</li>
                    </ul>
                </div>
                
                <form action="/proyecto-01/cliente/pages/confirmar_pedido.php" method="POST" class="confirm-form">
                    <button type="submit" name="confirmar_pedido" class="btn btn-primary">
                        Confirmar Pedido
                    </button>
                    <a href="/proyecto-01/cliente/pages/carrito.php" class="btn btn-secondary">Volver al Carrito</a>
                </form>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>
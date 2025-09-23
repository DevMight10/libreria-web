<?php
// 1. Incluir el controlador
require_once '../controllers/confirmar_pedido_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/cliente/styles/confirmar_pedido.css">

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
                    <br>
                    <a href="<?php echo BASE_URL; ?>/cliente/pages/libros.php" class="btn btn-primary">Seguir Viendo Libros</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="checkout-grid">
                <!-- Columna Izquierda: Información -->
                <div class="checkout-info">
                    <div class="info-section">
                        <h2>1. Datos de Contacto</h2>
                        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
                        <p>Usaremos estos datos para contactarte y coordinar la entrega.</p>
                        <a href="<?php echo BASE_URL; ?>/cliente/pages/perfil.php" class="link-editar">Editar perfil</a>
                    </div>

                    <div class="info-section">
                        <h2>2. Pasos Finales y Confirmación</h2>
                        <ul>
                            <li><strong>Revisa tu Pedido:</strong> Asegúrate de que los libros y las cantidades en el resumen de la derecha sean correctas.</li>
                            <li><strong>Registro en el Sistema:</strong> Al hacer clic en "Confirmar Pedido", tu solicitud quedará registrada en nuestro sistema.</li>
                            <li><strong>Coordinación Manual:</strong> Nuestro equipo de ventas se pondrá en contacto contigo para coordinar personalmente los detalles de la entrega y el pago.</li>
                        </ul>
                    </div>
                </div>

                <!-- Columna Derecha: Resumen del Pedido -->
                <div class="checkout-summary">
                    <h2>Resumen del Pedido</h2>
                    <div class="summary-items">
                        <?php foreach ($carrito_items as $libro_id => $item): ?>
                            <div class="summary-item">
                                <span class="item-qty"><?php echo $item['cantidad']; ?>x</span>
                                <span class="item-name"><?php echo htmlspecialchars($item['nombre']); ?></span>
                                <span class="item-price"><?php echo formatPrice($item['precio'] * $item['cantidad']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr>
                    <div class="summary-totals">
                        <div class="total-line">
                            <span>Subtotal:</span>
                            <span><?php echo formatPrice($cart->getTotal()); ?></span>
                        </div>
                        <div class="total-line">
                            <span>Envío:</span>
                            <span>A coordinar</span>
                        </div>
                        <hr>
                        <div class="total-line final-total">
                            <span>Total del Pedido:</span>
                            <span><?php echo formatPrice($cart->getTotal()); ?></span>
                        </div>
                    </div>
                    <form action="<?php echo BASE_URL; ?>/cliente/pages/confirmar_pedido.php" method="POST" class="confirm-form">
                        <button type="submit" name="confirmar_pedido" class="btn btn-primary btn-full-width">
                            Confirmar Pedido
                        </button>
                    </form>
                    <div class="back-link-container">
                        <a href="<?php echo BASE_URL; ?>/cliente/pages/carrito.php" class="link-volver">Volver al Carrito</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>
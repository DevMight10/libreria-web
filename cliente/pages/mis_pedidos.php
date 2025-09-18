<?php
// 1. Incluir el controlador
require_once '../controllers/mis_pedidos_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/cliente/styles/mis_pedidos.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="accordion">
        <?php if (empty($pedidos)): ?>
            <p>Aún no has realizado ningún pedido.</p>
            <a href="/proyecto-01/cliente/pages/productos.php" class="btn btn-primary">Ver productos</a>
        <?php else: ?>
            <?php foreach ($pedidos as $pedido_id => $pedido): ?>
                <div class="accordion-item">
                    <button class="accordion-header">
                        <div class="order-summary-info">
                            <span class="order-number">Pedido: <?php echo htmlspecialchars($pedido['numero_pedido']); ?></span>
                            <span class="order-date"><?php echo date('d/m/Y', strtotime($pedido['fecha_pedido'])); ?></span>
                        </div>
                        <div class="order-summary-status">
                            <span class="badge badge-<?php echo htmlspecialchars($pedido['estado']); ?>"><?php echo htmlspecialchars(formatOrderStatus($pedido['estado'])); ?></span>
                            <span class="order-total"><?php echo formatPrice($pedido['total']); ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </button>
                    <div class="accordion-content">
                        <div class="order-details">
                            <h4>Detalles del Pedido</h4>
                            <?php foreach ($pedido['productos'] as $producto): ?>
                                <div class="product-item">
                                    <img src="/proyecto-01/public/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                                    <div class="product-info">
                                        <?php echo htmlspecialchars($producto['nombre']); ?>
                                        <small>Cantidad: <?php echo $producto['cantidad']; ?></small>
                                    </div>
                                    <div class="product-price">
                                        <?php echo formatPrice($producto['precio_unitario'] * $producto['cantidad']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if ($pedido['estado'] === 'pendiente'): ?>
                                <div class="cancel-form">
                                    <form method="POST" onsubmit="return confirm('¿Estás seguro de que quieres cancelar este pedido?');">
                                        <input type="hidden" name="pedido_id" value="<?php echo $pedido_id; ?>">
                                        <button type="submit" name="cancelar_pedido" class="btn btn-danger">Cancelar Pedido</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>
<script src="/proyecto-01/cliente/public/js/accordion.js" defer></script>
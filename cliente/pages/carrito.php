<?php
// 1. Incluir el controlador
require_once '../controllers/carrito_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/cliente/styles/cart.css">

<!-- 4. Contenido HTML -->
<main>
    <div class="container">
        <?php if ($mensaje): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <h1>Carrito de Compras</h1>

        <?php if ($carrito_vacio): ?>
            <div class="empty-cart">
                <p>Tu carrito está vacío</p>
                <a href="/proyecto-01/cliente/pages/productos.php" class="btn btn-primary">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <?php foreach ($carrito_items as $producto_id => $item): ?>
                    <?php $max_quantity = $stocks[$producto_id] ?? 1; ?>
                    <div class="cart-item">
                        <img src="/proyecto-01/public/<?php echo htmlspecialchars($item['imagen']); ?>"
                            alt="<?php echo htmlspecialchars($item['nombre']); ?>">

                        <div class="cart-item-info">
                            <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                            <p class="price"><?php echo formatPrice($item['precio']); ?></p>
                        </div>

                        <div class="cart-item-actions">
                            <form action="/proyecto-01/cliente/pages/carrito.php" method="POST" class="quantity-form">
                                <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                                <div class="quantity-controls">
                                    <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>"
                                        min="1" max="<?php echo $max_quantity; ?>">
                                    <button type="submit" name="update_quantity" class="btn btn-secondary">
                                        Actualizar
                                    </button>
                                </div>
                            </form>

                            <form action="/proyecto-01/cliente/pages/carrito.php" method="POST" class="remove-form">
                                <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
                                <button type="submit" name="remove_item" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>

                        <div class="item-total">
                            <?php echo formatPrice($item['precio'] * $item['cantidad']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <div class="cart-total">
                    <h3>Total: <?php echo formatPrice($carrito_total); ?></h3>
                </div>

                <div class="cart-actions">
                    <form action="/proyecto-01/cliente/pages/carrito.php" method="POST" style="display: inline;">
                        <button type="submit" name="clear_cart" class="btn btn-secondary">
                            Vaciar Carrito
                        </button>
                    </form>
                    <a href="/proyecto-01/cliente/pages/confirmar_pedido.php" class="btn btn-primary">
                        Continuar
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>
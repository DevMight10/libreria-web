<?php
// 1. Incluir el controlador
require_once '../controllers/libro_detalle_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>
<link rel="stylesheet" href="/proyecto-01/cliente/styles/libro-detalle.css">


<main>
    <?php if (isset($_GET['mensaje'])): ?>
        <?php
        // Si el mensaje contiene "stock" o "agotado", lo mostramos en rojo
        $tipo = (strpos($_GET['mensaje'], 'stock') !== false || strpos($_GET['mensaje'], 'Agotado') !== false) ? 'error' : 'success';
        ?>
        <div class="notificacion <?php echo $tipo; ?>">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <div class="product-detail">
            <div class="product-image">
                <img src="/proyecto-01/public/<?php echo $libro['imagen']; ?>"
                    alt="<?php echo htmlspecialchars($libro['nombre']); ?>">
            </div>

            <div class="product-info">
                <h1><?php echo htmlspecialchars($libro['nombre']); ?></h1>
                <p class="category">Género: <?php echo htmlspecialchars($libro['genero_nombre']); ?></p>
                <p class="price"><?php echo formatPrice($libro['precio']); ?></p>

                <div class="product-description">
                    <h3>Descripción</h3>
                    <p><?php echo nl2br(htmlspecialchars($libro['descripcion'])); ?></p>
                </div>

                <p class="stock <?php echo ($libro['stock'] <= 5 && $libro['stock'] > 0) ? 'low-stock' : ''; ?>">
                    <?php
                    if ($libro['stock'] > 0) {
                        echo 'Disponibles: ' . $libro['stock'];
                    } else {
                        echo '<span class="out-of-stock">Agotado</span>';
                    }
                    ?>
                </p>

                <?php if (isLoggedIn()): ?>
                    <?php if ($libro['stock'] > 0): ?>
                        <form action="/proyecto-01/cliente/pages/carrito.php" method="POST" class="add-to-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                            <input type="hidden" name="return_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <div class="quantity-selector">
                                <label for="cantidad">Cantidad:</label>
                                <input type="number" id="cantidad" name="cantidad" value="1" min="1" max="<?php echo $libro['stock']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cart-plus"></i> Agregar al Carrito
                            </button>
                        </form>
                    <?php else: ?>
                        <p>Este libro no está disponible actualmente.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/proyecto-01/auth/login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>"
                        class="btn btn-primary">Inicia sesión para comprar</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>


<?php include '../../public/componentes/footer.php'; ?>
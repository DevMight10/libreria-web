<?php
// 1. Incluir el controlador
require_once '../controllers/productos_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/cliente/styles/lista-productos.css">

<!-- 4. Contenido HTML -->
<main>
    <div class="container">
        <?php if ($mensaje): ?>
            <?php
            $tipo = (stripos($mensaje, 'stock') !== false || stripos($mensaje, 'no hay suficiente') !== false) ? 'error' : 'success';
            ?>
            <div class="notificacion <?php echo $tipo; ?>">
                <?php echo htmlspecialchars($mensaje); ?>
            </div>
        <?php endif; ?>

        <h1>Nuestros Productos</h1>

        <div class="search-bar">
            <form action="/proyecto-01/cliente/pages/productos.php" method="GET">
                <input type="text" name="buscar" placeholder="Buscar productos..." value="<?php echo htmlspecialchars($buscar_filtro); ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <div class="filters">
            <a href="/proyecto-01/cliente/pages/productos.php" class="filter-btn <?php echo !$categoria_filtro ? 'active' : ''; ?>">
                Todos
            </a>
            <?php foreach ($categorias as $categoria): ?>
                <a href="/proyecto-01/cliente/pages/productos.php?categoria=<?php echo $categoria['id']; ?>"
                    class="filter-btn <?php echo $categoria_filtro == $categoria['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="products-grid">
            <?php if (empty($productos)): ?>
                <p>No se encontraron productos que coincidan con tu búsqueda.</p>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="product-card">
                        <img src="/proyecto-01/public/<?php echo $producto['imagen']; ?>"
                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="category"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></p>
                            <p class="price"><?php echo formatPrice($producto['precio']); ?></p>
                            <p class="stock <?php echo ($producto['stock'] <= 5 && $producto['stock'] > 0) ? 'low-stock' : ''; ?>">
                                <?php
                                if ($producto['stock'] > 0) {
                                    echo 'Disponibles: ' . $producto['stock'];
                                } else {
                                    echo '<span class="out-of-stock">Agotado</span>';
                                }
                                ?>
                            </p>
                            <div class="product-actions">
                                <a href="/proyecto-01/cliente/pages/producto_detalle.php?id=<?php echo $producto['id']; ?>"
                                    class="btn btn-secondary">Ver Detalles</a>
                                <?php if (isLoggedIn()): ?>
                                    <?php if ($producto['stock'] > 0): ?>
                                        <form action="/proyecto-01/cliente/pages/carrito.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                            <input type="hidden" name="return_url" value="/proyecto-01/cliente/pages/productos.php?<?php echo http_build_query($_GET); ?>">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-cart-plus"></i> Agregar
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-primary" disabled>Agotado</button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="/proyecto-01/auth/login.php" class="btn btn-primary">Inicia sesión para comprar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>

<style>
    .notificacion {
        padding: 1rem 1.5rem;
        margin: 1rem 0;
        border-radius: 5px;
        font-weight: bold;
        color: #fff;
    }
    .notificacion.success { background-color: #28a745; }
    .notificacion.error { background-color: #dc3545; }
</style>
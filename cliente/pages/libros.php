<?php
// 1. Incluir el controlador
require_once '../controllers/libros_controller.php';

// Page-specific styles
$page_specific_styles = '<link rel="stylesheet" href="/proyecto-01/cliente/styles/lista-libros.css">';

// 2. Incluir el header
include '../../public/componentes/header.php';

?>

<!-- 4. Contenido HTML -->
<main class="container">
    <?php if ($mensaje): ?>
        <?php
        $tipo = (stripos($mensaje, 'stock') !== false || stripos($mensaje, 'no hay suficiente') !== false) ? 'error' : 'success';
        ?>
        <div class="notificacion <?php echo $tipo; ?>">
            <?php echo htmlspecialchars($mensaje); ?>
        </div>
    <?php endif; ?>

    <h1><?php echo htmlspecialchars($page_title); ?></h1>

    <div class="search-bar">
        <form action="/proyecto-01/cliente/pages/libros.php" method="GET">
            <input type="text" name="buscar" placeholder="Buscar por título, autor..." value="<?php echo htmlspecialchars($buscar_filtro); ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="filters">
        <a href="/proyecto-01/cliente/pages/libros.php" class="filter-btn <?php echo !$genero_filtro ? 'active' : ''; ?>">
            Todos
        </a>
        <?php foreach ($generos as $genero): ?>
            <a href="/proyecto-01/cliente/pages/libros.php?genero=<?php echo $genero['id']; ?>"
                class="filter-btn <?php echo $genero_filtro == $genero['id'] ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($genero['nombre']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="libros-grid">
        <?php if (empty($libros)): ?>
            <p>No se encontraron libros que coincidan con tu búsqueda.</p>
        <?php else: ?>
            <?php foreach ($libros as $libro): ?>
                <div class="libro-card">
                    <p class="stock <?php echo ($libro['stock'] <= 5 && $libro['stock'] > 0) ? 'low-stock' : ''; ?>">
                        <?php
                        if ($libro['stock'] > 0) {
                            echo 'Disponibles: ' . $libro['stock'];
                        } else {
                            echo '<span class="out-of-stock">Agotado</span>';
                        }
                        ?>
                    </p>
                    <a href="/proyecto-01/cliente/pages/libro_detalle.php?id=<?php echo $libro['id']; ?>">
                        <img src="/proyecto-01/public/<?php echo $libro['imagen']; ?>"
                            alt="<?php echo htmlspecialchars($libro['nombre']); ?>">
                    </a>
                    <div class="libro-info">
                        <h3><?php echo htmlspecialchars($libro['nombre']); ?></h3>
                        <p class="category"><?php echo htmlspecialchars($libro['genero_nombre']); ?></p>
                        <p class="price"><?php echo formatPrice($libro['precio']); ?></p>
                    </div>
                    <div class="libro-actions">
                        <a href="/proyecto-01/cliente/pages/libro_detalle.php?id=<?php echo $libro['id']; ?>"
                            class="btn btn-secondary">Ver Detalles</a>
                        <?php if (isLoggedIn()): ?>
                            <?php if ($libro['stock'] > 0): ?>
                                <form action="/proyecto-01/cliente/pages/carrito.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                                    <input type="hidden" name="return_url" value="/proyecto-01/cliente/pages/libros.php?<?php echo http_build_query($_GET); ?>">
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
            <?php endforeach; ?>
        <?php endif; ?>
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
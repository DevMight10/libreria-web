<?php
// 1. Incluir el controlador
require_once '../controllers/productos_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/administrador/styles/productos.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <a href="/proyecto-01/administrador/pages/agregar_producto.php" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Agregar Nuevo Producto
    </a>

    <div class="filters-bar">
        <div class="filters">
            <a href="?filtro_categoria=todos&buscar=<?php echo htmlspecialchars($buscar); ?>"
               class="filter-btn <?php echo ($filtro_categoria == 'todos') ? 'active' : ''; ?>">Todas</a>
            <?php foreach ($categorias as $categoria): ?>
                <a href="?filtro_categoria=<?php echo $categoria['id']; ?>&buscar=<?php echo htmlspecialchars($buscar); ?>"
                   class="filter-btn <?php echo ($filtro_categoria == $categoria['id']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="search-form">
            <form action="" method="GET">
                <input type="hidden" name="filtro_categoria" value="<?php echo htmlspecialchars($filtro_categoria); ?>">
                <input type="text" name="buscar" placeholder="Buscar por ID o nombre..." value="<?php echo htmlspecialchars($buscar); ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No se encontraron productos.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $producto): ?>
                        <tr class="<?php echo $producto['activo'] ? '' : 'inactive-row'; ?>">
                            <td><?php echo $producto['id']; ?></td>
                            <td>
                                <img src="/proyecto-01/public/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" width="50">
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($producto['categoria_nombre']); ?></td>
                            <td><?php echo formatPrice($producto['precio']); ?></td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $producto['activo'] ? 'activo' : 'inactivo'; ?>">
                                    <?php echo $producto['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/proyecto-01/administrador/pages/productos.php?destacar=1&id=<?php echo $producto['id']; ?>" class="btn-destacar">
                                    <?php if ($producto['destacado']): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <a href="/proyecto-01/administrador/pages/editar_producto.php?id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-secondary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($producto['activo']): ?>
                                    <a href="/proyecto-01/administrador/pages/productos.php?cambiar_estado=1&id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-warning" title="Desactivar" onclick="return confirm('¿Estás seguro de que quieres DESACTIVAR este producto?');">
                                        <i class="fas fa-eye-slash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="/proyecto-01/administrador/pages/productos.php?cambiar_estado=1&id=<?php echo $producto['id']; ?>" class="btn btn-sm btn-success" title="Activar" onclick="return confirm('¿Estás seguro de que quieres ACTIVAR este producto?');">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>

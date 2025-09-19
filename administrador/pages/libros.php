<?php
// 1. Incluir el controlador
require_once '../controllers/libros_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/administrador/styles/productos.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <a href="/proyecto-01/administrador/pages/agregar_libro.php" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Agregar Nuevo Libro
    </a>

    <div class="filters-bar">
        <div class="filters">
            <a href="?filtro_genero=todos&buscar=<?php echo htmlspecialchars($buscar); ?>"
               class="filter-btn <?php echo ($filtro_genero == 'todos') ? 'active' : ''; ?>">Todos</a>
            <?php foreach ($generos as $genero): ?>
                <a href="?filtro_genero=<?php echo $genero['id']; ?>&buscar=<?php echo htmlspecialchars($buscar); ?>"
                   class="filter-btn <?php echo ($filtro_genero == $genero['id']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($genero['nombre']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="search-form">
            <form action="" method="GET">
                <input type="hidden" name="filtro_genero" value="<?php echo htmlspecialchars($filtro_genero); ?>">
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
                    <th>Género</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Destacado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($libros)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center;">No se encontraron libros.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($libros as $libro): ?>
                        <tr class="<?php echo $libro['activo'] ? '' : 'inactive-row'; ?>">
                            <td><?php echo $libro['id']; ?></td>
                            <td>
                                <img src="/proyecto-01/public/<?php echo htmlspecialchars($libro['imagen']); ?>" alt="<?php echo htmlspecialchars($libro['nombre']); ?>" width="50">
                            </td>
                            <td><?php echo htmlspecialchars($libro['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($libro['genero_nombre']); ?></td>
                            <td><?php echo formatPrice($libro['precio']); ?></td>
                            <td><?php echo $libro['stock']; ?></td>
                            <td>
                                <span class="badge badge-<?php echo $libro['activo'] ? 'activo' : 'inactivo'; ?>">
                                    <?php echo $libro['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/proyecto-01/administrador/pages/libros.php?destacar=1&id=<?php echo $libro['id']; ?>" class="btn-destacar">
                                    <?php if ($libro['destacado']): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                </a>
                            </td>
                            <td>
                                <a href="/proyecto-01/administrador/pages/editar_libro.php?id=<?php echo $libro['id']; ?>" class="btn btn-sm btn-secondary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($libro['activo']): ?>
                                    <a href="/proyecto-01/administrador/pages/libros.php?cambiar_estado=1&id=<?php echo $libro['id']; ?>" class="btn btn-sm btn-warning" title="Desactivar" onclick="return confirm('¿Estás seguro de que quieres DESACTIVAR este libro?');">
                                        <i class="fas fa-eye-slash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="/proyecto-01/administrador/pages/libros.php?cambiar_estado=1&id=<?php echo $libro['id']; ?>" class="btn btn-sm btn-success" title="Activar" onclick="return confirm('¿Estás seguro de que quieres ACTIVAR este libro?');">
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

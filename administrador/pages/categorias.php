<?php
// 1. Incluir el controlador, que se encarga de toda la lógica
require_once '../controllers/categorias_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS específico de la página -->
<link rel="stylesheet" href="/proyecto-01/administrador/styles/categorias.css">

<!-- 4. Contenido HTML de la página -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="category-grid">
        <div class="form-container">
            <h2>Agregar Nueva Categoría</h2>
            <form action="/proyecto-01/administrador/pages/categorias.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="nombre">Nombre de la Categoría</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Categoría</button>
            </form>
        </div>

        <div class="table-responsive">
            <h2>Categorías Existentes</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Productos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categorias)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay categorías creadas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?php echo $categoria['id']; ?></td>
                                <td><?php echo htmlspecialchars($categoria['nombre']); ?></td>
                                <td><?php echo $categoria['product_count']; ?></td>
                                <td class="actions-cell-buttons">
                                    <a href="/proyecto-01/administrador/pages/editar_categoria.php?id=<?php echo $categoria['id']; ?>" class="btn btn-sm btn-secondary" title="Editar">Editar</a>
                                    <form action="/proyecto-01/administrador/pages/categorias.php" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta categoría?');">
                                        <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" <?php echo ($categoria['product_count'] > 0) ? 'disabled' : ''; ?>>Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>

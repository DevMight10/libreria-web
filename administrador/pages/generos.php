<?php
require_once '../../config/config.php';

// 1. Incluir el controlador, que se encarga de toda la lógica
require_once '../controllers/generos_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS específico de la página -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/administrador/styles/categorias.css">

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
            <h2>Agregar Nuevo Género</h2>
            <form action="<?php echo BASE_URL; ?>/administrador/pages/generos.php" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="nombre">Nombre del Género</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Agregar Género</button>
            </form>
        </div>

        <div class="table-responsive">
            <h2>Géneros Existentes</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Libros</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($generos)): ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No hay géneros creados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($generos as $genero): ?>
                            <tr>
                                <td><?php echo $genero['id']; ?></td>
                                <td><?php echo htmlspecialchars($genero['nombre']); ?></td>
                                <td><?php echo $genero['libro_count']; ?></td>
                                <td class="actions-cell-buttons">
                                    <a href="<?php echo BASE_URL; ?>/administrador/pages/editar_genero.php?id=<?php echo $genero['id']; ?>" class="btn btn-sm btn-secondary" title="Editar">Editar</a>
                                    <form action="<?php echo BASE_URL; ?>/administrador/pages/generos.php" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este género?');">
                                        <input type="hidden" name="id" value="<?php echo $genero['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" <?php echo ($genero['libro_count'] > 0) ? 'disabled' : ''; ?>>Eliminar</button>
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
<?php
// 1. Incluir el controlador
require_once '../controllers/editar_categoria_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/administrador/styles/editar_categoria.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <a href="/proyecto-01/administrador/pages/categorias.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Categorías
    </a>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form action="/proyecto-01/administrador/pages/editar_categoria.php?id=<?php echo $categoria['id']; ?>" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre de la Categoría</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($categoria['nombre']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>

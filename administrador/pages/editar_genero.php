<?php
// 1. Incluir el controlador
require_once '../../config/config.php';

require_once '../controllers/editar_genero_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/administrador/styles/editar_categoria.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <a href="<?php echo BASE_URL; ?>/administrador/pages/generos.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Géneros
    </a>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form action="<?php echo BASE_URL; ?>/administrador/pages/editar_genero.php?id=<?php echo $genero['id']; ?>" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre del Género</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($genero['nombre']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>
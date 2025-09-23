<?php
require_once '../../config/config.php';

// 1. Incluir el controlador
require_once '../controllers/editar_usuario_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS (podemos crear uno o reutilizar) -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/administrador/styles/editar_categoria.css"> <!-- Reutilizando el estilo de editar categoría -->

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <a href="<?php echo BASE_URL; ?>/administrador/pages/usuarios.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Usuarios
    </a>

    <?php if (isset($error)):
        echo "<div class='alert alert-danger'>" . htmlspecialchars($error) . "</div>";
    endif; ?>

    <div class="form-container">
        <form action="<?php echo BASE_URL; ?>/administrador/pages/editar_usuario.php?id=<?php echo $usuario['id']; ?>" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
                <small>El email no se puede modificar.</small>
            </div>
            <div class="form-group">
                <label for="tipo">Rol</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="cliente" <?php echo ($usuario['tipo'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    <option value="admin" <?php echo ($usuario['tipo'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>
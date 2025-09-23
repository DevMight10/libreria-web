<?php
// 1. Incluir el controlador
require_once '../controllers/perfil_controller.php';
require_once '../../config/config.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS (podemos crear uno nuevo) -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/cliente/styles/perfil.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <div class="profile-grid">
        
        <div class="profile-picture-card card">
            <h2>Foto de Perfil</h2>
            
            <?php if (isset($errors['picture'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errors['picture']); ?></div><?php endif; ?>
            <?php if (isset($mensajes['picture'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensajes['picture']); ?></div><?php endif; ?>

            <?php 
                $foto_url = !empty($usuario['foto_perfil']) 
                    ? BASE_URL . '/public/' . htmlspecialchars($usuario['foto_perfil']) 
                    : BASE_URL . '/public/placeholder-user.jpg';
            ?>
            <img src="<?php echo $foto_url; ?>" alt="Foto de perfil" class="profile-pic">
            
            <button id="change-pic-btn" class="btn btn-secondary">Cambiar Foto</button>

            <div id="upload-form-container" class="upload-form-container">
                <form action="<?php echo BASE_URL; ?>/cliente/pages/perfil.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group file-input-wrapper">
                        <label for="foto_perfil" class="custom-file-upload">Seleccionar Foto</label>
                        <input type="file" name="foto_perfil" id="foto_perfil" required>
                    </div>
                    <span id="file-name-display"></span>
                    <button type="submit" name="upload_picture" class="btn btn-primary">Actualizar Foto</button>
                    <button type="button" id="cancel-upload-btn" class="btn btn-cancel">Cancelar</button>
                </form>
            </div>
        </div>

        <div class="profile-info card">
            <h2>Mis Datos</h2>
            <?php if (isset($mensajes['info'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensajes['info']); ?></div><?php endif; ?>
            <?php if (isset($errors['info'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errors['info']); ?></div><?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/cliente/pages/perfil.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
                    <small>El email no se puede modificar.</small>
                </div>
                <button type="submit" name="update_info" class="btn btn-primary">Guardar Nombre</button>
            </form>
        </div>

        <div class="profile-password card">
            <h2>Cambiar Contraseña</h2>
            <?php if (isset($mensajes['password'])): ?><div class="alert alert-success"><?php echo htmlspecialchars($mensajes['password']); ?></div><?php endif; ?>
            <?php if (isset($errors['password'])): ?><div class="alert alert-danger"><?php echo htmlspecialchars($errors['password']); ?></div><?php endif; ?>

            <form action="<?php echo BASE_URL; ?>/cliente/pages/perfil.php" method="POST">
                <div class="form-group">
                    <label for="current_password">Contraseña Actual</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Nueva Contraseña</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmar Nueva Contraseña</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-primary">Cambiar Contraseña</button>
            </form>
            <?php if ($usuario['password_actualizado_en']):
                echo '<small class="last-update">Última actualización: ' . date('d/m/Y H:i', strtotime($usuario['password_actualizado_en'])) . '</small>';
            endif; ?>
        </div>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>
<script src="<?php echo BASE_URL; ?>/public/js/perfil.js" defer></script>
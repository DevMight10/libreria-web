<?php
require_once '../../config/config.php';

// 1. Incluir el controlador
require_once '../controllers/usuarios_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS (crearemos uno nuevo) -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/administrador/styles/usuarios.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

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
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No hay usuarios registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?php echo $usuario['id']; ?></td>
                            <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $usuario['tipo'] == 'admin' ? 'admin' : 'cliente'; ?>">
                                    <?php echo ucfirst($usuario['tipo']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                            <td class="actions-cell">
                                <a href="<?php echo BASE_URL; ?>/administrador/pages/editar_usuario.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-secondary" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo BASE_URL; ?>/administrador/pages/usuarios.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                    <input type="hidden" name="action" value="toggle_admin">
                                    <button type="submit" class="btn btn-sm <?php echo $usuario['tipo'] == 'admin' ? 'btn-warning' : 'btn-success'; ?>"
                                        <?php echo $usuario['id'] == $_SESSION['usuario_id'] ? 'disabled' : ''; ?>>
                                        <?php echo $usuario['tipo'] == 'admin' ? 'Quitar Admin' : 'Hacer Admin'; ?>
                                    </button>
                                </form>
                                <form action="<?php echo BASE_URL; ?>/administrador/pages/usuarios.php" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este usuario? Esta acción no se puede deshacer.');">
                                    <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"
                                        <?php echo $usuario['id'] == $_SESSION['usuario_id'] ? 'disabled' : ''; ?>>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
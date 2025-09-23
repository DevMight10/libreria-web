<?php
// 1. Cargar dependencias
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';
require_once '../models/UsuarioModel.php';

requireAdmin();

// 2. Inicializar Modelo
$usuarioModel = new UsuarioModel($pdo);

$page_title = 'Gestionar Usuarios';
$error = null;
$mensaje = $_GET['mensaje'] ?? null;

// 3. Lógica de negocio (Manejo de acciones)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $user_id = $_POST['id'] ?? 0;

    // Evitar que el admin se elimine a sí mismo o al único admin
    if ($action === 'delete' && $user_id == $_SESSION['usuario_id']) {
        $error = "No puedes eliminar tu propia cuenta.";
    } else {
        if ($action === 'toggle_admin') {
            $user = $usuarioModel->find($user_id);
            if ($user) {
                $nuevo_tipo = ($user['tipo'] == 'admin') ? 'cliente' : 'admin';
                if ($usuarioModel->updateType($user_id, $nuevo_tipo)) {
                    header("Location: " . BASE_URL . "/administrador/pages/usuarios.php?mensaje=Rol de usuario actualizado.");
                    exit;
                } else {
                    $error = "Error al actualizar el rol del usuario.";
                }
            }
        } elseif ($action === 'delete') {
            if ($usuarioModel->delete($user_id)) {
                header("Location: " . BASE_URL . "/administrador/pages/usuarios.php?mensaje=Usuario eliminado.");
                exit;
            } else {
                $error = "Error al eliminar el usuario.";
            }
        }
    }
}

// 4. Obtener datos para la vista
$usuarios = $usuarioModel->getAll();

?>

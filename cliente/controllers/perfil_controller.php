<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../../administrador/models/UsuarioModel.php'; // Reutilizamos el modelo

requireLogin();

// 2. Inicializar Modelo
$usuarioModel = new UsuarioModel($pdo);

$page_title = 'Mi Perfil';
$mensaje = null;
$error = null;
$user_id = $_SESSION['usuario_id'];

// 3. Lógica de negocio
// Obtener datos del usuario para mostrar en la vista
$usuario = $usuarioModel->find($user_id);

// Procesar el formulario de cambio de contraseña
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validaciones
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Las nuevas contraseñas no coinciden.";
    } else {
        // Verificar la contraseña actual
        $password_hash_db = $usuarioModel->getPasswordHash($user_id);
        if (password_verify($current_password, $password_hash_db)) {
            // Hashear y guardar la nueva contraseña
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            if ($usuarioModel->changePassword($user_id, $new_password_hash)) {
                $mensaje = "Contraseña actualizada con éxito.";
            } else {
                $error = "Error al actualizar la contraseña.";
            }
        } else {
            $error = "La contraseña actual es incorrecta.";
        }
    }
}
?>

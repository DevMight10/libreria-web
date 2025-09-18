<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../../administrador/models/UsuarioModel.php';

requireLogin();

// 2. Inicializar Modelo
$usuarioModel = new UsuarioModel($pdo);

$page_title = 'Mi Perfil';
$user_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Acción: Actualizar nombre
    if (isset($_POST['update_info'])) {
        $nombre = $_POST['nombre'] ?? '';
        $usuario_actual = $usuarioModel->find($user_id);
        if (empty($nombre)) {
            $_SESSION['flash_errors']['info'] = "El nombre no puede estar vacío.";
        } elseif ($nombre === $usuario_actual['nombre']) {
            $_SESSION['flash_messages']['info'] = "No se realizaron cambios en el nombre.";
        } elseif ($usuarioModel->updateProfileInfo($user_id, $nombre)) {
            $_SESSION['usuario_nombre'] = $nombre; // Actualizar el nombre en la sesión
            $_SESSION['flash_messages']['info'] = "Nombre actualizado con éxito.";
        } else {
            $_SESSION['flash_errors']['info'] = "Error al actualizar el nombre.";
        }
    }

    // Acción: Cambiar contraseña
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['flash_errors']['password'] = "Todos los campos de contraseña son obligatorios.";
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['flash_errors']['password'] = "Las nuevas contraseñas no coinciden.";
        } else {
            $password_hash_db = $usuarioModel->getPasswordHash($user_id);
            if (password_verify($current_password, $password_hash_db)) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                if ($usuarioModel->changePassword($user_id, $new_password_hash)) {
                    $_SESSION['flash_messages']['password'] = "Contraseña actualizada con éxito.";
                } else {
                    $_SESSION['flash_errors']['password'] = "Error al actualizar la contraseña.";
                }
            } else {
                $_SESSION['flash_errors']['password'] = "La contraseña actual es incorrecta.";
            }
        }
    }

    // Acción: Subir foto de perfil
    if (isset($_POST['upload_picture'])) {
        $error_upload = null;
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
            $ruta_imagen = uploadAvatar($_FILES['foto_perfil'], $error_upload);
            if ($ruta_imagen) {
                $usuario_actual = $usuarioModel->find($user_id);
                if (!empty($usuario_actual['foto_perfil']) && file_exists(PROJECT_ROOT . '/public/' . $usuario_actual['foto_perfil'])) {
                    unlink(PROJECT_ROOT . '/public/' . $usuario_actual['foto_perfil']);
                }
                
                if ($usuarioModel->updateProfilePicture($user_id, $ruta_imagen)) {
                    $_SESSION['flash_messages']['picture'] = "Foto de perfil actualizada.";
                } else {
                    $_SESSION['flash_errors']['picture'] = "Error al guardar la ruta de la imagen.";
                }
            } else {
                 $_SESSION['flash_errors']['picture'] = $error_upload;
            }
        } else {
            $_SESSION['flash_errors']['picture'] = "No se seleccionó ningún archivo o hubo un error en la subida.";
        }
    }

    header('Location: /proyecto-01/cliente/pages/perfil.php');
    exit;
}

// 4. Obtener datos y mensajes para la vista
$mensajes = $_SESSION['flash_messages'] ?? [];
$errors = $_SESSION['flash_errors'] ?? [];
unset($_SESSION['flash_messages'], $_SESSION['flash_errors']); // Limpiar después de leer

$usuario = $usuarioModel->find($user_id);

?>

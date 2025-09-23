<?php
// 1. Cargar dependencias
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../models/UsuarioModel.php';

requireAdmin();

// 2. Inicializar Modelo
$usuarioModel = new UsuarioModel($pdo);

$page_title = 'Editar Usuario';
$error = null;

// 3. Lógica de negocio
// Validar que se haya pasado un ID
if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "/administrador/pages/usuarios.php");
    exit;
}
$id = $_GET['id'];

// Obtener datos del usuario
$usuario = $usuarioModel->find($id);

// Si el usuario no existe, redirigir
if (!$usuario) {
    header("Location: " . BASE_URL . "/administrador/pages/usuarios.php?error=Usuario no encontrado");
    exit;
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'tipo' => $_POST['tipo'] ?? ''
    ];

    if (empty($data['nombre']) || empty($data['tipo'])) {
        $error = "Todos los campos son obligatorios.";
    } elseif ($usuario['id'] == $_SESSION['usuario_id'] && $data['tipo'] !== 'admin') {
        $error = "No puedes cambiar tu propio rol a 'cliente'.";
    } else {
        if ($usuarioModel->update($id, $data)) {
            header("Location: " . BASE_URL . "/administrador/pages/usuarios.php?mensaje=Usuario actualizado con éxito");
            exit;
        } else {
            $error = "Error al actualizar el usuario.";
        }
    }
}
?>

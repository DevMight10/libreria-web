<?php
// 1. Cargar dependencias
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../models/Genero.php';

requireAdmin();

// 2. Inicializar Modelo
$generoModel = new GeneroModel($pdo);

$page_title = 'Editar Género';
$error = null;

// 3. Lógica de negocio
// Validar que se haya pasado un ID
if (!isset($_GET['id'])) {
    header("Location: " . BASE_URL . "/administrador/pages/generos.php");
    exit;
}
$id = $_GET['id'];

// Obtener datos del género
$genero = $generoModel->find($id);

// Si el género no existe, redirigir
if (!$genero) {
    header("Location: " . BASE_URL . "/administrador/pages/generos.php?error=Género no encontrado");
    exit;
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';

    if (empty($nombre)) {
        $error = "El nombre no puede estar vacío.";
    } elseif ($generoModel->update($id, $nombre)) {
        header("Location: " . BASE_URL . "/administrador/pages/generos.php?mensaje=Género actualizado con éxito");
        exit;
    } else {
        $error = "Error al actualizar el género.";
    }
}
?>
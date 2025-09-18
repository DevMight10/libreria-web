<?php
// 1. Cargar dependencias
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../models/Categoria.php';

requireAdmin();

// 2. Inicializar Modelo
$categoriaModel = new CategoriaModel($pdo);

$page_title = 'Editar Categoría';
$error = null;

// 3. Lógica de negocio
// Validar que se haya pasado un ID
if (!isset($_GET['id'])) {
    header("Location: /proyecto-01/administrador/pages/categorias.php");
    exit;
}
$id = $_GET['id'];

// Obtener datos de la categoría
$categoria = $categoriaModel->find($id);

// Si la categoría no existe, redirigir
if (!$categoria) {
    header("Location: /proyecto-01/administrador/pages/categorias.php?error=Categoría no encontrada");
    exit;
}

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';

    if (empty($nombre)) {
        $error = "El nombre no puede estar vacío.";
    } elseif ($categoriaModel->update($id, $nombre)) {
        header("Location: /proyecto-01/administrador/pages/categorias.php?mensaje=Categoría actualizada con éxito");
        exit;
    } else {
        $error = "Error al actualizar la categoría.";
    }
}
?>

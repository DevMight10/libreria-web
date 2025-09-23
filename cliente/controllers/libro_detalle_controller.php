<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../../administrador/models/Libro.php';

// 2. Inicializar Modelo
$libroModel = new LibroModel($pdo);

// 3. Lógica de negocio
$libro_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($libro_id <= 0) {
    header('Location: ' . BASE_URL . '/cliente/pages/libros.php');
    exit();
}

$libro = $libroModel->find($libro_id);

// Si el libro no existe o no está activo, redirigir
if (!$libro || !$libro['activo']) {
    header('Location: ' . BASE_URL . '/cliente/pages/libros.php');
    exit();
}

$page_title = $libro['nombre'];

// Mensaje de notificación (ej. 'stock insuficiente')
$mensaje = $_GET['mensaje'] ?? null;

?>
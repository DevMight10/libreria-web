<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../../administrador/models/Libro.php'; 

// 2. Inicializar Modelo
$libroModel = new LibroModel($pdo);

$page_title = 'Inicio';

// 3. Obtener datos para la vista
$filtros = [
    'activo' => 1,
    'destacado' => 1,
    'limit' => 6
];
$libros_destacados = $libroModel->getAll($filtros);

?>
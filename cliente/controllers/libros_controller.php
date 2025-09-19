<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../../administrador/models/Libro.php'; 

// 2. Inicializar Modelo
$libroModel = new LibroModel($pdo);

$page_title = 'Nuestro Catálogo';

// 3. Lógica de negocio (Filtros y Búsqueda)
$genero_filtro = $_GET['genero'] ?? '';
$buscar_filtro = $_GET['buscar'] ?? '';

$filtros = [
    'genero_id' => $genero_filtro,
    'buscar' => $buscar_filtro,
    'activo' => 1 // En la tienda solo mostramos libros activos
];

// 4. Obtener datos para la vista
$libros = $libroModel->getAll($filtros);
$generos = $libroModel->getGeneros();

// Mensaje de notificación (ej. 'libro agregado al carrito')
$mensaje = $_GET['mensaje'] ?? null;

?>
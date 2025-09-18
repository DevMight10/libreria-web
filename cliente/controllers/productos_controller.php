<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../../administrador/models/Producto.php'; 

// 2. Inicializar Modelo
$productoModel = new ProductoModel($pdo);

$page_title = 'Productos';

// 3. Lógica de negocio (Filtros y Búsqueda)
$categoria_filtro = $_GET['categoria'] ?? '';
$buscar_filtro = $_GET['buscar'] ?? '';

$filtros = [
    'categoria_id' => $categoria_filtro,
    'buscar' => $buscar_filtro,
    'activo' => 1 // En la tienda solo mostramos productos activos
];

// 4. Obtener datos para la vista
$productos = $productoModel->getAll($filtros);
$categorias = $productoModel->getCategorias();

// Mensaje de notificación (ej. 'producto agregado al carrito')
$mensaje = $_GET['mensaje'] ?? null;

?>

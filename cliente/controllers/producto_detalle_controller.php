<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../../administrador/models/Producto.php';

// 2. Inicializar Modelo
$productoModel = new ProductoModel($pdo);

// 3. Lógica de negocio
$producto_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($producto_id <= 0) {
    header('Location: /proyecto-01/cliente/pages/productos.php');
    exit();
}

$producto = $productoModel->find($producto_id);

// Si el producto no existe o no está activo, redirigir
if (!$producto || !$producto['activo']) {
    header('Location: /proyecto-01/cliente/pages/productos.php');
    exit();
}

$page_title = $producto['nombre'];

// Mensaje de notificación (ej. 'stock insuficiente')
$mensaje = $_GET['mensaje'] ?? null;

?>

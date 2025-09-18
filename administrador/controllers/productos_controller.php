<?php
// 1. Cargar dependencias
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';
require_once '../models/Producto.php'; // Cargar el nuevo modelo

requireAdmin();

// 2. Inicializar Modelo
$productoModel = new ProductoModel($pdo);

$page_title = 'Gestionar Productos';
$error = null;
$mensaje = $_GET['mensaje'] ?? null;

// 3. Lógica de negocio (Manejo de acciones)

// Acción para cambiar estado 'activo'
if (isset($_GET['cambiar_estado']) && isset($_GET['id'])) {
    if ($productoModel->toggleStatus($_GET['id'], 'activo')) {
        header("Location: /proyecto-01/administrador/pages/productos.php?mensaje=Estado del producto actualizado con éxito");
        exit;
    } else {
        $error = "Error al actualizar el estado del producto o producto no encontrado.";
    }
}

// Acción para cambiar estado 'destacado'
if (isset($_GET['destacar']) && isset($_GET['id'])) {
    if ($productoModel->toggleStatus($_GET['id'], 'destacado')) {
        header("Location: /proyecto-01/administrador/pages/productos.php?mensaje=Producto actualizado");
        exit;
    } else {
        $error = "Error al actualizar el producto o producto no encontrado.";
    }
}

// 4. Obtener datos para la vista (con filtros)
$filtro_categoria = $_GET['filtro_categoria'] ?? 'todos';
$buscar = $_GET['buscar'] ?? '';

$filtros = [
    'categoria_id' => $filtro_categoria,
    'buscar' => $buscar
];

$productos = $productoModel->getAll($filtros);
$categorias = $productoModel->getCategorias(); // Para el menú de filtros

?>

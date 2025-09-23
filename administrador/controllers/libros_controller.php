<?php
// 1. Cargar dependencias
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';
require_once '../models/Libro.php';

requireAdmin();

// 2. Inicializar Modelo
$libroModel = new LibroModel($pdo);

$page_title = 'Gestionar Libros';
$error = null;
$mensaje = $_GET['mensaje'] ?? null;

// 3. Lógica de negocio (Manejo de acciones)

// Acción para cambiar estado 'activo'
if (isset($_GET['cambiar_estado']) && isset($_GET['id'])) {
    if ($libroModel->toggleStatus($_GET['id'], 'activo')) {
        header("Location: " . BASE_URL . "/administrador/pages/libros.php?mensaje=Estado del libro actualizado con éxito");
        exit;
    } else {
        $error = "Error al actualizar el estado del libro o libro no encontrado.";
    }
}

// Acción para cambiar estado 'destacado'
if (isset($_GET['destacar']) && isset($_GET['id'])) {
    if ($libroModel->toggleStatus($_GET['id'], 'destacado')) {
        header("Location: " . BASE_URL . "/administrador/pages/libros.php?mensaje=Libro actualizado");
        exit;
    } else {
        $error = "Error al actualizar el libro o libro no encontrado.";
    }
}

// 4. Obtener datos para la vista (con filtros)
$filtro_genero = $_GET['filtro_genero'] ?? 'todos';
$buscar = $_GET['buscar'] ?? '';

$filtros = [
    'genero_id' => $filtro_genero,
    'buscar' => $buscar
];

$libros = $libroModel->getAll($filtros);
$generos = $libroModel->getGeneros(); // Para el menú de filtros

?>
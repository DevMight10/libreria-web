<?php
// 1. Cargar dependencias
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../models/Categoria.php'; // Cargar el nuevo modelo

requireAdmin();

// 2. Inicializar Modelo
$categoriaModel = new CategoriaModel($pdo);

$page_title = 'Gestionar Categorías';
$error = $_GET['error'] ?? null;
$mensaje = $_GET['mensaje'] ?? null;

// 3. Lógica de negocio (Manejo de peticiones)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add') {
        $nombre = $_POST['nombre'] ?? '';
        if (empty($nombre)) {
            $error = "El nombre de la categoría no puede estar vacío.";
        } elseif ($categoriaModel->add($nombre)) {
            header("Location: /proyecto-01/administrador/pages/categorias.php?mensaje=Categoría agregada con éxito");
            exit;
        } else {
            $error = "Error al agregar la categoría.";
        }
    }

    if ($action == 'delete') {
        $id = $_POST['id'] ?? 0;
        if ($categoriaModel->isUsed($id)) {
            header("Location: /proyecto-01/administrador/pages/categorias.php?error=No se puede eliminar, la categoría está en uso.");
            exit;
        } elseif ($categoriaModel->delete($id)) {
            header("Location: /proyecto-01/administrador/pages/categorias.php?mensaje=Categoría eliminada con éxito");
            exit;
        } else {
            $error = "Error al eliminar la categoría.";
        }
    }
}

// 4. Obtener datos para la vista
$categorias = $categoriaModel->getAll();

// El controlador no incluye el header/footer, eso lo hace la vista.
?>

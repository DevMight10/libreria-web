<?php
// 1. Cargar dependencias
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../models/Genero.php';

requireAdmin();

// 2. Inicializar Modelo
$generoModel = new GeneroModel($pdo);

$page_title = 'Gestionar Géneros';
$error = $_GET['error'] ?? null;
$mensaje = $_GET['mensaje'] ?? null;

// 3. Lógica de negocio (Manejo de peticiones)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add') {
        $nombre = $_POST['nombre'] ?? '';
        if (empty($nombre)) {
            $error = "El nombre del género no puede estar vacío.";
        } elseif ($generoModel->add($nombre)) {
            header("Location: " . BASE_URL . "/administrador/pages/generos.php?mensaje=Género agregado con éxito");
            exit;
        } else {
            $error = "Error al agregar el género.";
        }
    }

    if ($action == 'delete') {
        $id = $_POST['id'] ?? 0;
        if ($generoModel->isUsed($id)) {
            header("Location: " . BASE_URL . "/administrador/pages/generos.php?error=No se puede eliminar, el género está en uso.");
            exit;
        } elseif ($generoModel->delete($id)) {
            header("Location: " . BASE_URL . "/administrador/pages/generos.php?mensaje=Género eliminado con éxito");
            exit;
        } else {
            $error = "Error al eliminar el género.";
        }
    }
}

// 4. Obtener datos para la vista
$generos = $generoModel->getAll();

?>
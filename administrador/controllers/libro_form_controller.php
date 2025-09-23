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

// 3. Lógica de negocio
$error = null;
$libro_id = $_GET['id'] ?? null;
$is_edit = (bool)$libro_id;

if ($is_edit) {
    $page_title = 'Editar Libro';
    $libro = $libroModel->find($libro_id);
    if (!$libro) {
        header("Location: " . BASE_URL . "/administrador/pages/libros.php?mensaje=Libro no encontrado");
        exit;
    }
} else {
    $page_title = 'Agregar Libro';
    // Inicializar un array de libro vacío para que el formulario no de errores
    $libro = [
        'id' => null, 'nombre' => '', 'autor' => '', 'descripcion' => '', 'precio' => '',
        'genero_id' => null, 'stock' => 0, 'activo' => 1, 'destacado' => 0, 'imagen' => ''
    ];
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'autor' => $_POST['autor'] ?? '',
        'descripcion' => $_POST['descripcion'] ?? '',
        'precio' => $_POST['precio'] ?? 0,
        'genero_id' => $_POST['genero_id'] ?? null,
        'stock' => $_POST['stock'] ?? 0,
        'activo' => isset($_POST['activo']) ? 1 : 0,
        'destacado' => isset($_POST['destacado']) ? 1 : 0,
        'imagen' => $libro['imagen'] // Mantener la imagen actual por defecto
    ];

    // Validación básica
    if (empty($data['nombre']) || empty($data['genero_id']) || $data['precio'] <= 0) {
        $error = "Por favor, complete los campos obligatorios (Nombre, Género y Precio).";
    } else {
        // Manejo de la subida de imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $nueva_imagen = uploadImage($_FILES['imagen'], $error);
            if ($nueva_imagen) {
                // Si es una edición y había una imagen anterior, borrarla
                if ($is_edit && !empty($libro['imagen']) && file_exists(PROJECT_ROOT . '/public/' . $libro['imagen'])) {
                    unlink(PROJECT_ROOT . '/public/' . $libro['imagen']);
                }
                $data['imagen'] = $nueva_imagen;
            }
        }

        if (!$error) {
            $success = false;
            if ($is_edit) {
                if ($libroModel->update($libro_id, $data)) {
                    $success = true;
                    $mensaje = "Libro actualizado con éxito";
                }
            } else {
                if ($libroModel->add($data)) {
                    $success = true;
                    $mensaje = "Libro agregado con éxito";
                }
            }

            if ($success) {
                header("Location: " . BASE_URL . "/administrador/pages/libros.php?mensaje=" . urlencode($mensaje));
                exit;
            } else {
                $error = "Error al guardar el libro en la base de datos.";
            }
        }
    }
}

// Obtener géneros para el selector del formulario
$generos = $libroModel->getGeneros();

?>
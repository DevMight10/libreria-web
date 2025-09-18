<?php
// 1. Cargar dependencias
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';
require_once '../models/Producto.php';

requireAdmin();

// 2. Inicializar Modelo
$productoModel = new ProductoModel($pdo);

// 3. Lógica de negocio
$error = null;
$producto_id = $_GET['id'] ?? null;
$is_edit = (bool)$producto_id;

if ($is_edit) {
    $page_title = 'Editar Producto';
    $producto = $productoModel->find($producto_id);
    if (!$producto) {
        header("Location: /proyecto-01/administrador/pages/productos.php?mensaje=Producto no encontrado");
        exit;
    }
} else {
    $page_title = 'Agregar Producto';
    // Inicializar un array de producto vacío para que el formulario no de errores
    $producto = [
        'id' => null, 'nombre' => '', 'descripcion' => '', 'precio' => '',
        'categoria_id' => null, 'stock' => 0, 'activo' => 1, 'destacado' => 0, 'imagen' => ''
    ];
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'descripcion' => $_POST['descripcion'] ?? '',
        'precio' => $_POST['precio'] ?? 0,
        'categoria_id' => $_POST['categoria_id'] ?? null,
        'stock' => $_POST['stock'] ?? 0,
        'activo' => isset($_POST['activo']) ? 1 : 0,
        'destacado' => isset($_POST['destacado']) ? 1 : 0,
        'imagen' => $producto['imagen'] // Mantener la imagen actual por defecto
    ];

    // Validación básica
    if (empty($data['nombre']) || empty($data['categoria_id']) || $data['precio'] <= 0) {
        $error = "Por favor, complete los campos obligatorios (Nombre, Categoría y Precio).";
    } else {
        // Manejo de la subida de imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $nueva_imagen = uploadImage($_FILES['imagen'], $error);
            if ($nueva_imagen) {
                // Si es una edición y había una imagen anterior, borrarla
                if ($is_edit && !empty($producto['imagen']) && file_exists(PROJECT_ROOT . '/public/' . $producto['imagen'])) {
                    unlink(PROJECT_ROOT . '/public/' . $producto['imagen']);
                }
                $data['imagen'] = $nueva_imagen;
            }
        }

        if (!$error) {
            $success = false;
            if ($is_edit) {
                if ($productoModel->update($producto_id, $data)) {
                    $success = true;
                    $mensaje = "Producto actualizado con éxito";
                }
            } else {
                if ($productoModel->add($data)) {
                    $success = true;
                    $mensaje = "Producto agregado con éxito";
                }
            }

            if ($success) {
                header("Location: /proyecto-01/administrador/pages/productos.php?mensaje=" . urlencode($mensaje));
                exit;
            } else {
                $error = "Error al guardar el producto en la base de datos.";
            }
        }
    }
}

// Obtener categorías para el selector del formulario
$categorias = $productoModel->getCategorias();

?>

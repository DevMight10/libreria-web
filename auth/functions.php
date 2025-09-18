<?php
function generateOrderNumber() {
    return 'MC' . date('Ymd') . rand(1000, 9999);
}

function formatPrice($price) {
    return 'Bs. ' . number_format($price, 2);
}

function getCartTotal() {
    $total = 0;
    if (isset($_SESSION['carrito'])) {
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
    }
    return $total;
}

function getCartItemCount() {
    return isset($_SESSION['carrito']) ? count($_SESSION['carrito']) : 0;
}

function addToCart($producto_id, $nombre, $precio, $imagen, $stock, $cantidad = 1) {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }
    
    if (isset($_SESSION['carrito'][$producto_id])) {
        $_SESSION['carrito'][$producto_id]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$producto_id] = array(
            'nombre' => $nombre,
            'precio' => $precio,
            'imagen' => $imagen,
            'cantidad' => $cantidad,
            'stock' => $stock
        );
    }
}

function formatOrderStatus($status) {
    return ucfirst(str_replace('_', ' ', $status));
}

function uploadImage($file, &$error) {
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024; // 5 MB

    if ($file['size'] > $max_size) {
        $error = "El archivo es demasiado grande. El tamaño máximo permitido es 5 MB.";
        return null;
    }

    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($file_info, $file['tmp_name']);
    finfo_close($file_info);

    if (!in_array($mime_type, $allowed_types)) {
        $error = "Tipo de archivo no permitido. Solo se aceptan imágenes JPG y PNG.";
        return null;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid('prod_') . '.' . strtolower($extension);
    $ruta_archivo = PROJECT_ROOT . '/public/' . $nombre_archivo;

    if (move_uploaded_file($file['tmp_name'], $ruta_archivo)) {
        return $nombre_archivo;
    } else {
        $error = "Error al mover el archivo subido.";
        return null;
    }
}

function uploadAvatar($file, &$error) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2 MB

    if ($file['size'] > $max_size) {
        $error = "El archivo es demasiado grande. El tamaño máximo es 2 MB.";
        return null;
    }

    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($file_info, $file['tmp_name']);
    finfo_close($file_info);

    if (!in_array($mime_type, $allowed_types)) {
        $error = "Tipo de archivo no permitido. Solo se aceptan JPG, PNG y GIF.";
        return null;
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid('avatar_') . '_' . time() . '.' . strtolower($extension);
    
    // Ruta relativa a la raíz del proyecto para guardar en la BD
    $ruta_relativa = 'uploads/avatars/' . $nombre_archivo;
    // Ruta absoluta para mover el archivo
    $ruta_absoluta = PROJECT_ROOT . '/public/' . $ruta_relativa;

    if (move_uploaded_file($file['tmp_name'], $ruta_absoluta)) {
        return $ruta_relativa; // Devolver la ruta relativa para guardarla en la BD
    } else {
        $error = "Error al mover el archivo subido.";
        return null;
    }
}
?>

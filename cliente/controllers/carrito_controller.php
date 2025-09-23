<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../../administrador/models/Libro.php'; // Needed to check stock

requireLogin();

// 2. Inicializar Modelos
$cart = new Cart($pdo, $_SESSION['usuario_id']);
$libroModel = new LibroModel($pdo);

$page_title = 'Carrito de Compras';
$error = $_SESSION['mensaje_error'] ?? null;
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje_error'], $_SESSION['mensaje']); // Limpiar mensajes

// 3. Lógica de negocio (Manejo de acciones)

// Acción: Agregar al carrito
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $libro_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
    $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : BASE_URL . '/cliente/pages/libros.php';

    $libro = $libroModel->find($libro_id);

    if ($libro && $libro['activo']) {
        $cantidad_en_carrito = $_SESSION['carrito'][$libro_id]['cantidad'] ?? 0;
        if (($cantidad_en_carrito + $cantidad) > $libro['stock']) {
            $msg = "No hay suficiente stock para agregar la cantidad solicitada.";
        } else {
            $cart->add($libro['id'], $libro['nombre'], $libro['precio'], $libro['imagen'], $cantidad);
            $msg = "Libro agregado al carrito";
        }
    } else {
        $msg = "El libro no existe o no está disponible";
    }
    
    // Redirigir con mensaje
    $separator = (strpos($return_url, '?') === false) ? '?' : '&';
    header("Location: " . $return_url . $separator . "mensaje=" . urlencode($msg));
    exit;
}

// Acciones desde la página del carrito (actualizar, eliminar, vaciar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_quantity'])) {
        $libro_id = $_POST['libro_id'];
        $nueva_cantidad = (int)$_POST['cantidad'];
        $libro = $libroModel->find($libro_id);

        if ($nueva_cantidad > $libro['stock']) {
            $_SESSION['mensaje_error'] = "No hay suficiente stock. Disponibles: {$libro['stock']}.";
        } else {
            $cart->update($libro_id, $nueva_cantidad);
            $_SESSION['mensaje'] = "Cantidad actualizada.";
        }
    }

    if (isset($_POST['remove_item'])) {
        $cart->remove($_POST['libro_id']);
        $_SESSION['mensaje'] = "Libro eliminado del carrito.";
    }

    if (isset($_POST['clear_cart'])) {
        $cart->clear();
        $_SESSION['mensaje'] = "Se ha vaciado todo el carrito.";
    }

    // Redirigir a la misma página para evitar reenvío de formulario
    header('Location: ' . BASE_URL . '/cliente/pages/carrito.php');
    exit;
}


// 4. Obtener datos para la vista
$carrito_items = $cart->getItems();
$carrito_total = $cart->getTotal();
$carrito_vacio = empty($carrito_items);

// Obtener stock actualizado para los libros en el carrito
$stocks = [];
if (!$carrito_vacio) {
    $libro_ids = array_keys($carrito_items);
    $placeholders = implode(',', array_fill(0, count($libro_ids), '?'));
    $stmt_stocks = $pdo->prepare("SELECT id, stock FROM libros WHERE id IN ($placeholders)");
    $stmt_stocks->execute($libro_ids);
    $stocks = $stmt_stocks->fetchAll(PDO::FETCH_KEY_PAIR);
}

?>
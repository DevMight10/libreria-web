<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../../administrador/models/Producto.php'; // Needed to check stock

requireLogin();

// 2. Inicializar Modelos
$cart = new Cart($pdo, $_SESSION['usuario_id']);
$productoModel = new ProductoModel($pdo);

$page_title = 'Carrito de Compras';
$error = $_SESSION['mensaje_error'] ?? null;
$mensaje = $_SESSION['mensaje'] ?? null;
unset($_SESSION['mensaje_error'], $_SESSION['mensaje']); // Limpiar mensajes

// 3. Lógica de negocio (Manejo de acciones)

// Acción: Agregar al carrito (reemplaza agregar_al_carrito.php)
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $producto_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;
    $return_url = isset($_POST['return_url']) ? $_POST['return_url'] : '/proyecto-01/cliente/pages/productos.php';

    $producto = $productoModel->find($producto_id);

    if ($producto && $producto['activo']) {
        $cantidad_en_carrito = $_SESSION['carrito'][$producto_id]['cantidad'] ?? 0;
        if (($cantidad_en_carrito + $cantidad) > $producto['stock']) {
            $msg = "No hay suficiente stock para agregar la cantidad solicitada.";
        } else {
            $cart->add($producto['id'], $producto['nombre'], $producto['precio'], $producto['imagen'], $cantidad);
            $msg = "Producto agregado al carrito";
        }
    } else {
        $msg = "El producto no existe o no está disponible";
    }
    
    // Redirigir con mensaje
    $separator = (strpos($return_url, '?') === false) ? '?' : '&';
    header("Location: " . $return_url . $separator . "mensaje=" . urlencode($msg));
    exit;
}

// Acciones desde la página del carrito (actualizar, eliminar, vaciar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_quantity'])) {
        $producto_id = $_POST['producto_id'];
        $nueva_cantidad = (int)$_POST['cantidad'];
        $producto = $productoModel->find($producto_id);

        if ($nueva_cantidad > $producto['stock']) {
            $_SESSION['mensaje_error'] = "No hay suficiente stock. Disponibles: {$producto['stock']}.";
        } else {
            $cart->update($producto_id, $nueva_cantidad);
            $_SESSION['mensaje'] = "Cantidad actualizada.";
        }
    }

    if (isset($_POST['remove_item'])) {
        $cart->remove($_POST['producto_id']);
        $_SESSION['mensaje'] = "Producto eliminado del carrito.";
    }

    if (isset($_POST['clear_cart'])) {
        $cart->clear();
        $_SESSION['mensaje'] = "Se ha vaciado todo el carrito.";
    }

    // Redirigir a la misma página para evitar reenvío de formulario
    header('Location: /proyecto-01/cliente/pages/carrito.php');
    exit;
}


// 4. Obtener datos para la vista
$carrito_items = $cart->getItems();
$carrito_total = $cart->getTotal();
$carrito_vacio = empty($carrito_items);

// Obtener stock actualizado para los productos en el carrito
$stocks = [];
if (!$carrito_vacio) {
    $product_ids = array_keys($carrito_items);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $stmt_stocks = $pdo->prepare("SELECT id, stock FROM productos WHERE id IN ($placeholders)");
    $stmt_stocks->execute($product_ids);
    $stocks = $stmt_stocks->fetchAll(PDO::FETCH_KEY_PAIR);
}

?>

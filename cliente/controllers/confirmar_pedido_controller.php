<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../../administrador/models/UsuarioModel.php';

requireLogin();

// 2. Inicializar Modelos
$cart = new Cart($pdo, $_SESSION['usuario_id']);
$pedidoModel = new PedidoModel($pdo);
$usuarioModel = new UsuarioModel($pdo);

// Obtener datos del usuario logueado
$usuario = $usuarioModel->find($_SESSION['usuario_id']);

$page_title = 'Confirmar Pedido';
$mensaje = '';

// 3. Lógica de negocio
$carrito_items = $cart->getItems();

if (empty($carrito_items)) {
    header('Location: ' . BASE_URL . '/cliente/pages/libros.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirmar_pedido'])) {
    
    // 1. Pre-confirmación de stock
    $errores_stock = $pedidoModel->verificarStock($carrito_items);

    if (!empty($errores_stock)) {
        $_SESSION['mensaje_error'] = implode('<br>', $errores_stock);
        header('Location: ' . BASE_URL . '/cliente/pages/carrito.php');
        exit;
    }

    // 2. Procesar el pedido si hay stock
    $total = $cart->getTotal();
    $numero_pedido = $pedidoModel->crearPedido($_SESSION['usuario_id'], $carrito_items, $total);
    
    if ($numero_pedido) {
        $cart->clear(); // Limpiar carrito de sesión y BD
        $mensaje = "Pedido confirmado exitosamente. Número de pedido: $numero_pedido";
    } else {
        $mensaje = "Error al procesar el pedido. Inténtalo nuevamente.";
    }
}
?>

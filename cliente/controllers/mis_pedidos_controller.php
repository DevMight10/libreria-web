<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../models/PedidoModel.php';

requireLogin();

// 2. Inicializar Modelo
$pedidoModel = new PedidoModel($pdo);

$page_title = 'Mis Pedidos';
$usuario_id = $_SESSION['usuario_id'];
$mensaje = $_GET['mensaje'] ?? null;
$error = $_GET['error'] ?? null;

// 3. Lógica de negocio (Manejo de acciones)
if (isset($_POST['cancelar_pedido'])) {
    $pedido_id_a_cancelar = $_POST['pedido_id'];
    
    try {
        if ($pedidoModel->cancelarPedido($pedido_id_a_cancelar, $usuario_id)) {
            header("Location: /proyecto-01/cliente/pages/mis_pedidos.php?mensaje=Pedido cancelado exitosamente.");
            exit;
        }
    } catch (Exception $e) {
        header("Location: /proyecto-01/cliente/pages/mis_pedidos.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}

// 4. Obtener datos para la vista
$pedidos = $pedidoModel->getPedidosPorUsuario($usuario_id);

?>

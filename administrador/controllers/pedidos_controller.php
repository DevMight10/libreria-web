<?php
// 1. Cargar dependencias
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';
require_once '../models/Pedido.php'; // Cargar el nuevo modelo

requireAdmin();

// 2. Inicializar Modelo
$pedidoModel = new PedidoModel($pdo);

$page_title = 'Gestionar Pedidos';
$error = null;
$mensaje = $_GET['mensaje'] ?? null;

// 3. Lógica de negocio (Manejo de acciones)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id']) && isset($_POST['estado'])) {
    $pedido_id = $_POST['pedido_id'];
    $nuevo_estado = $_POST['estado'];
    
    // Aquí se podría añadir una lógica más robusta para validar transiciones de estado
    if ($pedidoModel->updateStatus($pedido_id, $nuevo_estado)) {
        $query_params = http_build_query([
            'filtro_estado' => $_GET['filtro_estado'] ?? 'todos',
            'buscar_codigo' => $_GET['buscar_codigo'] ?? '',
            'pagina' => $_GET['pagina'] ?? 1
        ]);
        header("Location: /proyecto-01/administrador/pages/pedidos.php?{$query_params}&mensaje=Estado del pedido actualizado.");
        exit;
    } else {
        $error = "Error al actualizar el estado.";
    }
}

// 4. Obtener datos para la vista (Paginación y Filtros)
$pedidos_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $pedidos_por_pagina;

$filtro_estado = $_GET['filtro_estado'] ?? 'todos';
$buscar_codigo = $_GET['buscar_codigo'] ?? '';

$filtros = [
    'estado' => $filtro_estado,
    'codigo' => $buscar_codigo
];

$total_pedidos = $pedidoModel->countAll($filtros);
$total_paginas = ceil($total_pedidos / $pedidos_por_pagina);

$pedidos = $pedidoModel->getAll($filtros, $offset, $pedidos_por_pagina);

?>

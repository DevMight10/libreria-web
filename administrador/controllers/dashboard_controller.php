<?php
// 1. Cargar dependencias
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';
require_once '../models/DashboardModel.php';

requireAdmin();

// 2. Inicializar Modelo
$dashboardModel = new DashboardModel($pdo);

$page_title = 'Panel de Administración';

// 3. Obtener todos los datos para la vista
$stats = $dashboardModel->getStats();
$ultimos_pedidos = $dashboardModel->getRecentOrders();
$sales_data = $dashboardModel->getSalesData();

// 4. Preparar datos de la gráfica para el view
$fechas = [];
$ventas = [];
foreach ($sales_data as $row) {
    $fechas[] = $row['dia'];
    $ventas[] = $row['total_dia'];
}

?>

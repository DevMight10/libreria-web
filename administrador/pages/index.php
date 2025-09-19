<?php
// 1. Incluir el controlador
require_once '../controllers/dashboard_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/administrador/styles/index.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <div class="admin-stats">
        <div class="stat-card">
            <h3><?= $stats['total_categorias'] ?></h3>
            <p>Categorías</p>
            <a href="/proyecto-01/administrador/pages/categorias.php">Ver Categorías</a>
        </div>
        <div class="stat-card">
            <h3><?= $stats['total_productos'] ?></h3>
            <p>Productos Activos</p>
            <a href="/proyecto-01/administrador/pages/productos.php">Gestionar</a>
        </div>
        <div class="stat-card">
            <h3><?= $stats['pedidos_pendientes'] ?></h3>
            <p>Pedidos Pendientes</p>
            <a href="/proyecto-01/administrador/pages/pedidos.php">Ver Pedidos</a>
        </div>
        <div class="stat-card">
            <h3><?= $stats['mensajes_nuevos'] ?></h3>
            <p>Mensajes Nuevos</p>
            <a href="/proyecto-01/administrador/pages/mensajes.php">Ver Mensajes</a>
        </div>
        <div class="stat-card">
            <h3><?= $stats['total_usuarios'] ?></h3>
            <p>Usuarios</p>
            <a href="/proyecto-01/administrador/pages/usuarios.php">Ver Usuarios</a>
        </div>
    </div>

    <div class="chart-container">
        <h2>Ventas Últimos 7 Días</h2>
        <canvas id="ventasChart" data-fechas='<?= json_encode($fechas) ?>' data-ventas='<?= json_encode($ventas) ?>'></canvas>
    </div>

    <div class="recent-orders">
        <h2>Últimos 5 Pedidos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ultimos_pedidos as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['numero_pedido']) ?></td>
                        <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                        <td><?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></td>
                        <td><?= formatPrice($pedido['total']) ?></td>
                        <td>
                            <span class="badge badge-<?= $pedido['estado'] ?>"><?= htmlspecialchars(formatOrderStatus($pedido['estado'])) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/proyecto-01/administrador/public/js/dashboard-chart.js"></script>

<?php
// 1. Incluir el controlador
require_once '../controllers/pedidos_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/administrador/styles/pedidos.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <div class="filters-bar">
        <div class="filters">
            <?php
            $estados = ['todos', 'pendiente', 'en_proceso', 'entregado', 'cancelado'];
            foreach ($estados as $estado) {
                $query_params = http_build_query(['filtro_estado' => $estado, 'buscar_codigo' => $buscar_codigo]);
                $active_class = ($filtro_estado == $estado) ? 'active' : '';
                echo "<a href=\"?{$query_params}\" class=\"filter-btn {$active_class}\">" . formatOrderStatus($estado) . "</a>";
            }
            ?>
        </div>
        <div class="search-form">
            <form action="" method="GET">
                <input type="hidden" name="filtro_estado" value="<?php echo htmlspecialchars($filtro_estado); ?>">
                <input type="text" name="buscar_codigo" placeholder="Buscar por código..." value="<?php echo htmlspecialchars($buscar_codigo); ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="order-list-container">
        <div class="order-list-header">
            <span class="col col-pedido">Pedido</span>
            <span class="col col-cliente">Cliente</span>
            <span class="col col-fecha">Fecha</span>
            <span class="col col-estado">Estado</span>
            <span class="col col-total">Total</span>
            <span class="col col-icono"></span>
        </div>

        <div class="accordion">
            <?php if (empty($pedidos)): ?>
                <p class="no-orders-found">No se encontraron pedidos que coincidan con los filtros aplicados.</p>
            <?php else: ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="accordion-item">
                        <button class="accordion-header">
                            <span class="col col-pedido"><?php echo htmlspecialchars($pedido['numero_pedido']); ?></span>
                            <span class="col col-cliente"><?php echo htmlspecialchars($pedido['cliente_nombre']); ?></span>
                            <span class="col col-fecha"><?php echo date('d/m/Y', strtotime($pedido['fecha_pedido'])); ?></span>
                            <span class="col col-estado">
                                <span class="badge badge-<?php echo htmlspecialchars($pedido['estado']); ?>">
                                    <?php echo formatOrderStatus($pedido['estado']); ?>
                                </span>
                            </span>
                            <span class="col col-total"><?php echo formatPrice($pedido['total']); ?></span>
                            <span class="col col-icono"><i class="fas fa-chevron-down"></i></span>
                        </button>

                        <div class="accordion-content">
                            <div class="order-details-admin">
                                <h4>Detalles del Pedido</h4>
                                <div class="customer-details">
                                    <strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['cliente_nombre']); ?><br>
                                    <strong>Email:</strong> <?php echo htmlspecialchars($pedido['cliente_email']); ?>
                                </div>
                                <hr>
                                <h5>Productos</h5>
                                <?php if (empty($pedido['productos'])): ?>
                                    <p>Este pedido no tiene productos asociados.</p>
                                <?php else: ?>
                                    <?php foreach ($pedido['productos'] as $producto): ?>
                                        <div class="product-item">
                                            <img src="/proyecto-01/public/<?php echo htmlspecialchars($producto['producto_imagen']); ?>" alt="<?php echo htmlspecialchars($producto['producto_nombre']); ?>">
                                            <div class="product-info">
                                                <?php echo htmlspecialchars($producto['producto_nombre']); ?>
                                                <small>Cantidad: <?php echo $producto['cantidad']; ?></small>
                                            </div>
                                            <div class="product-price">
                                                <?php echo formatPrice($producto['precio_unitario'] * $producto['cantidad']); ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <hr>
                                <div class="admin-actions">
                                    <h5>Acciones</h5>
                                    <?php if ($pedido['estado'] === 'pendiente'): ?>
                                        <form action="" method="POST">
                                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                                            <input type="hidden" name="estado" value="en_proceso">
                                            <button type="submit" class="btn btn-primary">Mover a "En Proceso"</button>
                                        </form>
                                    <?php elseif ($pedido['estado'] === 'en_proceso'): ?>
                                        <form action="" method="POST">
                                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                                            <input type="hidden" name="estado" value="entregado">
                                            <button type="submit" class="btn btn-success">Marcar como "Entregado"</button>
                                        </form>
                                    <?php else: ?>
                                        <p>No hay acciones disponibles.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="pagination">
        <?php
        if ($total_paginas > 1) {
            $query_params = http_build_query(['filtro_estado' => $filtro_estado, 'buscar_codigo' => $buscar_codigo]);
            if ($pagina_actual > 1) {
                echo "<a href=\"?{$query_params}&pagina=" . ($pagina_actual - 1) . "\">Anterior</a>";
            }
            for ($i = 1; $i <= $total_paginas; $i++) {
                $active_class = ($i == $pagina_actual) ? 'active' : '';
                echo "<a href=\"?{$query_params}&pagina={$i}\" class=\"{$active_class}\">{$i}</a>";
            }
            if ($pagina_actual < $total_paginas) {
                echo "<a href=\"?{$query_params}&pagina=" . ($pagina_actual + 1) . "\">Siguiente</a>";
            }
        }
        ?>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>
<script src="/proyecto-01/administrador/public/js/accordion.js" defer></script>

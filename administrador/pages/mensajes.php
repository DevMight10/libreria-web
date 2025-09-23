<?php
require_once '../../config/config.php';

// 1. Incluir el controlador
require_once '../controllers/mensajes_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/administrador/styles/mensaje.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <div class="filters-bar">
        <div class="filters">
            <a href="?filtro=todos&buscar=<?php echo htmlspecialchars($buscar); ?>" class="filter-btn <?php echo ($filtro == 'todos') ? 'active' : ''; ?>">Todos</a>
            <a href="?filtro=nuevos&buscar=<?php echo htmlspecialchars($buscar); ?>" class="filter-btn <?php echo ($filtro == 'nuevos') ? 'active' : ''; ?>">Nuevos</a>
            <a href="?filtro=leidos&buscar=<?php echo htmlspecialchars($buscar); ?>" class="filter-btn <?php echo ($filtro == 'leidos') ? 'active' : ''; ?>">Leídos</a>
        </div>
        <div class="search-form">
            <form action="" method="GET">
                <input type="hidden" name="filtro" value="<?php echo htmlspecialchars($filtro); ?>">
                <input type="text" name="buscar" placeholder="Buscar en mensajes..." value="<?php echo htmlspecialchars($buscar); ?>">
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

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>De</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mensajes)): ?>
                    <tr><td colspan="6" style="text-align: center;">No hay mensajes que coincidan con los criterios.</td></tr>
                <?php else: ?>
                    <?php foreach ($mensajes as $msg): ?>
                        <?php
                            $is_expanded = ($expanded_id === (int)$msg['id']);
                            $view_params = $current_params;
                            if ($is_expanded) {
                                unset($view_params['expand_id']);
                            } else {
                                $view_params['expand_id'] = $msg['id'];
                            }
                        ?>
                        <tr class="mensaje-summary-row <?php echo $msg['leido'] ? 'mensaje-leido' : 'mensaje-nuevo'; ?> <?php echo $is_expanded ? 'expanded' : ''; ?>">
                            <td>
                                <?php echo htmlspecialchars($msg['nombre']); ?><br>
                                <small><?php echo htmlspecialchars($msg['email']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($msg['asunto']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars(substr($msg['mensaje'], 0, 70))) . (strlen($msg['mensaje']) > 70 ? '...' : ''); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($msg['fecha_envio'])); ?></td>
                            <td><span class="badge badge-<?php echo $msg['leido'] ? 'leido' : 'nuevo'; ?>"><?php echo $msg['leido'] ? 'Leído' : 'Nuevo'; ?></span></td>
                            <td class="actions-cell">
                                <a href="?<?php echo http_build_query($view_params); ?>" class="btn btn-sm btn-primary">
                                    <?php echo $is_expanded ? 'Cerrar' : 'Ver'; ?>
                                </a>
                                <form action="<?php echo BASE_URL; ?>/administrador/pages/mensajes.php" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este mensaje?');">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" name="id" value="<?php echo $msg['id']; ?>">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar mensaje">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php if ($is_expanded): ?>
                            <tr class="mensaje-expandido">
                                <td colspan="6">
                                    <div class="mensaje-contenido-full">
                                        <h4>Mensaje Completo</h4>
                                        <p><strong>De:</strong> <?php echo htmlspecialchars($msg['nombre']); ?> (<?php echo htmlspecialchars($msg['email']); ?>)</p>
                                        <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i', strtotime($msg['fecha_envio'])); ?></p>
                                        <hr>
                                        <p><?php echo nl2br(htmlspecialchars($msg['mensaje'])); ?></p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo generar_paginacion($total_paginas, $pagina_actual, $current_params); ?>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>
<?php
// 1. Cargar dependencias
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../auth/session.php';
require_once '../../auth/functions.php';
require_once '../models/Mensaje.php'; // Cargar el nuevo modelo

requireAdmin();

// 2. Inicializar Modelo
$mensajeModel = new MensajeModel($pdo);

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page_title = 'Ver Mensajes';
$error = null;
$mensaje = $_GET['mensaje'] ?? null;

// 3. Lógica de negocio (Manejo de acciones)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Error de validación CSRF.');
    }

    if (isset($_POST['accion'])) {
        if ($_POST['accion'] == 'eliminar' && isset($_POST['id'])) {
            if ($mensajeModel->delete($_POST['id'])) {
                header("Location: " . BASE_URL . "/administrador/pages/mensajes.php?mensaje=Mensaje eliminado con éxito");
                exit;
            } else {
                $error = "Error al eliminar el mensaje.";
            }
        } elseif ($_POST['accion'] == 'marcar_leido' && isset($_POST['id'])) {
            $mensajeModel->markAsRead($_POST['id']);
            // La página se recarga con el GET, no es necesaria una redirección explícita
        }
    }
}

// 4. Obtener datos para la vista
$expanded_id = isset($_GET['expand_id']) ? (int)$_GET['expand_id'] : null;

if ($expanded_id) {
    $mensajeModel->markAsRead($expanded_id);
}

$mensajes_por_pagina = 15;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $mensajes_por_pagina;

$filtro = $_GET['filtro'] ?? 'todos';
$buscar = $_GET['buscar'] ?? '';

$current_params = ['filtro' => $filtro, 'buscar' => $buscar];

$filtros = [
    'estado' => $filtro,
    'buscar' => $buscar
];

$total_mensajes = $mensajeModel->countAll($filtros);
$total_paginas = ceil($total_mensajes / $mensajes_por_pagina);

$mensajes = $mensajeModel->getAll($filtros, $offset, $mensajes_por_pagina);

// Función para generar enlaces de paginación (se mantiene aquí por ahora)
function generar_paginacion($total_paginas, $pagina_actual, $params) {
    if ($total_paginas <= 1) return '';
    $html = '<div class="pagination">';
    for ($i = 1; $i <= $total_paginas; $i++) {
        $query_params = http_build_query(array_merge($params, ['pagina' => $i]));
        $active_class = ($i == $pagina_actual) ? 'active' : '';
        $html .= "<a href=\"?{$query_params}\" class=\"{$active_class}\">{$i}</a>";
    }
    $html .= '</div>';
    return $html;
}

?>
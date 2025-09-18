<?php
// 1. Cargar dependencias
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../auth/functions.php';
require_once __DIR__ . '/../models/ContactoModel.php';

// 2. Inicializar Modelo
$contactoModel = new ContactoModel($pdo);

$page_title = 'Contacto';
$mensaje = '';

// 3. Lógica de negocio
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nombre' => $_POST['nombre'],
        'email' => $_POST['email'],
        'asunto' => $_POST['asunto'],
        'mensaje' => $_POST['mensaje']
    ];
    
    if ($contactoModel->guardarMensaje($data)) {
        $mensaje = 'Mensaje enviado exitosamente. Te responderemos pronto.';
    } else {
        $mensaje = 'Error al enviar el mensaje. Por favor, inténtalo nuevamente.';
    }
}
?>

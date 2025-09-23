<?php
// Redirigir al index principal de la aplicación
require_once __DIR__ . '/config/config.php';
header('Location: ' . BASE_URL . '/cliente/pages/index.php');
exit();
?>
<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['usuario_id']);
}

function isAdmin() {
    return isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /proyecto-01/auth/login.php');
        exit();
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /proyecto-01/cliente/pages/index.php');
        exit();
    }
}
?>

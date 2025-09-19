<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// La ruta a functions.php puede necesitar ser ajustada si el header se incluye desde diferentes niveles.
// Usar __DIR__ asegura que la ruta sea siempre correcta desde la ubicación del header.php
require_once __DIR__ . '/../../auth/functions.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Mini Chic</title>
    
    <!-- <link rel="stylesheet" href="/proyecto-01/cliente/styles/base.css"> -->
    <link rel="stylesheet" href="/proyecto-01/cliente/styles/header.css">
    <link rel="stylesheet" href="/proyecto-01/public/global.css">
    <link rel="stylesheet" href="/proyecto-01/cliente/styles/footer.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <?php echo $page_specific_styles ?? ''; ?>
</head>
<body>
    <header class="site-header">
        <div class="container header-container">
            <a href="/proyecto-01/cliente/pages/index.php" class="logo">Mini Chic</a>

            <nav class="main-nav">
                <ul>
                    <li><a href="/proyecto-01/cliente/pages/index.php">Inicio</a></li>
                    <li><a href="/proyecto-01/cliente/pages/productos.php">Productos</a></li>
                    <li><a href="/proyecto-01/cliente/pages/contacto.php">Contacto</a></li>
                </ul>
            </nav>

            <div class="action-links">
                <?php if (isLoggedIn()): ?>
                    <a href="/proyecto-01/cliente/pages/carrito.php" class="icon-link cart-link" aria-label="Carrito">
                        <i class="fas fa-shopping-cart"></i>
                        <?php 
                        $itemCount = getCartItemCount();
                        if ($itemCount > 0): ?>
                            <span class="cart-count"><?php echo $itemCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="/proyecto-01/cliente/pages/mis_pedidos.php" class="icon-link" aria-label="Mis Pedidos">
                        <i class="fas fa-user"></i>
                    </a>
                    <?php if (isAdmin()): ?>
                        <a href="/proyecto-01/administrador/pages/index.php" class="icon-link" aria-label="Admin Panel">
                           <i class="fas fa-user-shield"></i>
                        </a>
                    <?php endif; ?>
                    <a href="/proyecto-01/auth/logout.php" class="icon-link" aria-label="Salir">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="/proyecto-01/auth/login.php" class="btn-login">Iniciar Sesión</a>
                    <a href="/proyecto-01/auth/registro.php" class="btn-register">Registrarse</a>
                <?php endif; ?>
            </div>

            <!-- <button class="mobile-menu-toggle" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button> -->
        </div>
    </header>

    <!-- Menú de Navegación Móvil -->
    <!-- <div class="mobile-nav">
         <nav>
            <ul>
                <li><a href="/proyecto-01/cliente/pages/index.php">Inicio</a></li>
                <li><a href="/proyecto-01/cliente/pages/productos.php">Productos</a></li>
                <li><a href="/proyecto-01/cliente/pages/contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </div> -->

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.querySelector('.mobile-menu-toggle');
        const mobileNav = document.querySelector('.mobile-nav');

        if (toggleBtn && mobileNav) {
            toggleBtn.addEventListener('click', function() {
                mobileNav.classList.toggle('is-active');
            });
        }
    });
</script> -->
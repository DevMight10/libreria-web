<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Admin - Librería Adrimarth</title>

    <!-- Header específico para admin con rutas relativas correctas -->
    <link rel="stylesheet" href="/proyecto-01/administrador/styles/header.css">
    <link rel="stylesheet" href="/proyecto-01/public/global.css">
    <link rel="stylesheet" href="/proyecto-01/administrador/styles/footer.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="nav-brand">
                <h1>Librería Adrimarth - Admin</h1>
                <p>Panel de Administración</p>
            </div>
            <nav class="nav-menu">
                <a href="/proyecto-01/cliente/pages/index.php">Ver Sitio</a>
                <a href="/proyecto-01/administrador/pages/index.php">Dashboard</a>
                <a href="/proyecto-01/administrador/pages/generos.php">Géneros</a> 
                <a href="/proyecto-01/administrador/pages/libros.php">Libros</a>
                <a href="/proyecto-01/administrador/pages/pedidos.php">Pedidos</a>
                <a href="/proyecto-01/administrador/pages/mensajes.php">Mensajes</a>
                <a href="/proyecto-01/administrador/pages/usuarios.php">Usuarios</a>
                <a href="/proyecto-01/auth/logout.php">Salir</a>
            </nav>
        </div>
    </header>
</body>
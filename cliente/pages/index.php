<?php
// 1. Incluir el controlador
require_once '../controllers/index_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/cliente/styles/home.css">

<!-- 4. Contenido HTML -->
<main>
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Ropa adorable para tu <span style="color : #007B44">pequeno tesoro</span> </h1>
                <p>Descubre nuestra colección de ropa para bebés, diseñada con amor y fabricada con los materiales más suaves y seguros.</p>
                <div>
                    <a href="/proyecto-01/cliente/pages/productos.php" class="btn">Ver Productos</a>
                </div>
            </div>
            <div class="hero-img">
                <img src="/proyecto-01/public/imgs/bebe.jpg" alt="hero">
            </div>
        </div>
    </section>

    <section class="search-section">
        <div class="container">
            <form action="/proyecto-01/cliente/pages/productos.php" method="GET" class="search-form">
                <input type="text" name="buscar" placeholder="¿Qué estás buscando?">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>
    </section>

    <section class="detalles-home">
        <div class="container features">
            <div class="feature">
                <span class="icon"><i class="fa-solid fa-truck-fast"></i></span>
                <h3>Envío Gratuito</h3>
                <p>En compras mayores a Bs500</p>
            </div>
            <div class="feature">
                <span class="icon"><i class="fa-solid fa-shield"></i></span>
                <h3>Materiales Seguros</h3>
                <p>Algodón orgánico, sin químicos</p>
            </div>
            <div class="feature">
                <span class="icon"><i class="fa-solid fa-clock"></i></span>
                <h3>Atención 24/7</h3>
                <p>Soporte para todas tus consultas</p>
            </div>
        </div>
    </section>

    <section class="featured-products">
        <div class="container">
            <h2 style="text-align: center;">Productos Destacados</h2>
            <div class="products-grid">
                <?php foreach ($productos_destacados as $producto): ?>
                    <div class="product-card">
                        <img src="/proyecto-01/public/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="price"><?php echo formatPrice($producto['precio']); ?></p>
                        <a href="/proyecto-01/cliente/pages/producto_detalle.php?id=<?php echo $producto['id']; ?>" class="btn">Ver Detalles</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>

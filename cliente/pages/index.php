<?php
// 1. Incluir el controlador
require_once '../controllers/index_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/cliente/styles/home.css">

<!-- 4. Contenido HTML
    <!-- Hero Section -->
    <section class="hero" style="background-image: url('/proyecto-01/public/imgs/bebe.jpg');">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1>Ropa adorable para tu <span class="highlight">pequeño tesoro</span></h1>
            <p>Diseñada con amor, fabricada con los materiales más suaves y seguros.</p>
            <div class="hero-actions">
                <a href="/proyecto-01/cliente/pages/productos.php" class="btn btn-primary">Ver Colección</a>
                <form action="/proyecto-01/cliente/pages/productos.php" method="GET" class="hero-search">
                    <input type="text" name="buscar" placeholder="Buscar mamelucos, gorritos...">
                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                </form>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container features">
            <div class="feature">
                <div class="icon"><i class="fa-solid fa-truck-fast"></i></div>
                <h3>Envío Rápido y Gratis</h3>
                <p>En compras mayores a Bs500</p>
            </div>
            <div class="feature">
                <div class="icon"><i class="fa-solid fa-leaf"></i></div>
                <h3>100% Algodón Orgánico</h3>
                <p>Materiales seguros, sin químicos</p>
            </div>
            <div class="feature">
                <div class="icon"><i class="fa-solid fa-headset"></i></div>
                <h3>Atención 24/7</h3>
                <p>Soporte para todas tus consultas</p>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="container">
            <h2>Productos Destacados</h2>
            <p class="section-subtitle">Los favoritos de nuestros clientes</p>
            <div class="products-grid">
                <?php foreach ($productos_destacados as $producto): ?>
                    <div class="product-card">
                        <a href="/proyecto-01/cliente/pages/producto_detalle.php?id=<?php echo $producto['id']; ?>" class="product-image-link">
                            <img src="/proyecto-01/public/<?php echo htmlspecialchars($producto['imagen']); ?>" 
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        </a>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="price"><?php echo formatPrice($producto['precio']); ?></p>
                            <a href="/proyecto-01/cliente/pages/producto_detalle.php?id=<?php echo $producto['id']; ?>" class="btn btn-secondary">Ver Detalles</a>
                        </div>
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

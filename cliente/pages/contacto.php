<?php
// 1. Incluir el controlador
require_once '../controllers/contacto_controller.php';

// 2. Incluir el header
include '../../public/componentes/header.php';
?>

<!-- 3. Link al CSS -->
<link rel="stylesheet" href="/proyecto-01/cliente/styles/contacto.css">
<!-- <link rel="stylesheet" href="/proyecto-01/cliente/styles/header.css"> -->

<!-- 4. Contenido HTML -->
<main>
    <div class="container">
        <h1><?php echo $page_title; ?></h1>
        
        <div class="contact-content">
            <div class="contact-info">
                <h2>Información de Contacto</h2>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Dirección</h3>
                        <p>Satélite Norte, La Paz - Bolivia</p>
                    </div>j
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Teléfono</h3>
                        <p>+591 2 123-4567</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>info@minichic.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>Horarios</h3>
                        <p>Lunes a Sábado: 9:00 - 18:00</p>
                        <p>Domingo: 10:00 - 16:00</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form">
                <h2>Envíanos un Mensaje</h2>
                
                <?php if ($mensaje): ?>
                    <div class="alert <?php echo strpos($mensaje, 'exitosamente') !== false ? 'alert-success' : 'alert-error'; ?>">
                        <?php echo $mensaje; ?>
                    </div>
                <?php endif; ?>
                
                <form action="/proyecto-01/cliente/pages/contacto.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="asunto">Asunto:</label>
                        <select id="asunto" name="asunto" required>
                            <option value="">Selecciona un asunto</option>
                            <option value="Consulta sobre productos">Consulta sobre productos</option>
                            <option value="Problema con pedido">Problema con pedido</option>
                            <option value="Sugerencia">Sugerencia</option>
                            <option value="Reclamo">Reclamo</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje:</label>
                        <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/footer.php';
?>

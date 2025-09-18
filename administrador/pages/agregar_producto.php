<?php
// 1. Incluir el controlador unificado
require_once '../controllers/producto_form_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<!-- 3. Link al CSS (puede ser uno común para ambos formularios) -->
<link rel="stylesheet" href="/proyecto-01/administrador/styles/agregar_producto.css">

<!-- 4. Contenido HTML -->
<main class="container">
    <h1><?php echo $page_title; ?></h1>

    <a href="/proyecto-01/administrador/pages/productos.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Productos
    </a>

    <?php if (isset($error)):
        echo "<div class='alert alert-danger'>{$error}</div>";
    endif; ?>

    <form action="/proyecto-01/administrador/pages/agregar_producto.php" method="POST" enctype="multipart/form-data" class="form-container">
        
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="precio">Precio (Bs.)</label>
            <input type="number" id="precio" name="precio" class="form-control" step="0.01" value="<?php echo htmlspecialchars($producto['precio']); ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría</label>
            <select id="categoria_id" name="categoria_id" class="form-control" required>
                <option value="">Seleccione una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php echo ($producto['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?php echo htmlspecialchars($producto['stock']); ?>" required>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del Producto</label>
            <input type="file" id="imagen" name="imagen" class="form-control">
        </div>

        <div class="form-group form-check">
            <input type="checkbox" id="activo" name="activo" class="form-check-input" value="1" <?php echo $producto['activo'] ? 'checked' : ''; ?>>
            <label for="activo" class="form-check-label">Producto Activo</label>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" id="destacado" name="destacado" class="form-check-input" value="1" <?php echo $producto['destacado'] ? 'checked' : ''; ?>>
            <label for="destacado" class="form-check-label">Marcar como Producto Destacado</label>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Producto</button>
    </form>
</main>

<?php
// 5. Incluir el footer
include '../../public/componentes/admin_footer.php';
?>

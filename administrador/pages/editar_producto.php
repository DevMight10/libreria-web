<?php
// 1. Incluir el controlador unificado
require_once '../controllers/producto_form_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<link rel="stylesheet" href="/proyecto-01/administrador/styles/editar.css">

<main class="container">
    <h1>Editar Producto</h1>
    
    <a href="/proyecto-01/administrador/pages/productos.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Productos
    </a>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="/proyecto-01/administrador/pages/editar_producto.php?id=<?php echo $producto_id; ?>" method="POST" enctype="multipart/form-data" class="form-container">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
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
            <label for="imagen">Imagen Actual</label>
            <div>
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="/proyecto-01/public/<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen actual" width="100">
                <?php else: ?>
                    <p>No hay imagen asignada.</p>
                <?php endif; ?>
            </div>
            <label for="imagen" class="mt-2">Subir Nueva Imagen (opcional)</label>
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
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</main>

<?php include '../../public/componentes/admin_footer.php'; ?>  


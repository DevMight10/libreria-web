<?php
require_once '../../config/config.php';

// 1. Incluir el controlador unificado
require_once '../controllers/libro_form_controller.php';

// 2. Incluir el header
include '../../public/componentes/admin_header.php';
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/administrador/styles/editar.css">

<main class="container">
    <h1>Editar Libro</h1>
    
    <a href="<?php echo BASE_URL; ?>/administrador/pages/libros.php" class="btn btn-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Volver a Libros
    </a>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>/administrador/pages/editar_libro.php?id=<?php echo $libro_id; ?>" method="POST" enctype="multipart/form-data" class="form-container">
        <div class="form-group">
            <label for="nombre">Nombre del Libro</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($libro['nombre']); ?>" required>
        </div>

        <div class="form-group">
            <label for="autor">Autor</label>
            <input type="text" id="autor" name="autor" class="form-control" value="<?php echo htmlspecialchars($libro['autor']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required><?php echo htmlspecialchars($libro['descripcion']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="precio">Precio (Bs.)</label>
            <input type="number" id="precio" name="precio" class="form-control" step="0.01" value="<?php echo htmlspecialchars($libro['precio']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="genero_id">Género</label>
            <select id="genero_id" name="genero_id" class="form-control" required>
                <?php foreach ($generos as $genero): ?>
                    <option value="<?php echo $genero['id']; ?>" <?php echo ($libro['genero_id'] == $genero['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($genero['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?php echo htmlspecialchars($libro['stock']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="imagen">Imagen Actual</label>
            <div>
                <?php if (!empty($libro['imagen'])): ?>
                    <img src="<?php echo BASE_URL; ?>/public/<?php echo htmlspecialchars($libro['imagen']); ?>" alt="Imagen actual" width="100">
                <?php else: ?>
                    <p>No hay imagen asignada.</p>
                <?php endif; ?>
            </div>
            <label for="imagen" class="mt-2">Subir Nueva Imagen (opcional)</label>
            <input type="file" id="imagen" name="imagen" class="form-control">
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" id="activo" name="activo" class="form-check-input" value="1" <?php echo $libro['activo'] ? 'checked' : ''; ?>>
            <label for="activo" class="form-check-label">Libro Activo</label>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" id="destacado" name="destacado" class="form-check-input" value="1" <?php echo $libro['destacado'] ? 'checked' : ''; ?>>
            <label for="destacado" class="form-check-label">Marcar como Libro Destacado</label>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</main>

<?php include '../../public/componentes/admin_footer.php'; ?>

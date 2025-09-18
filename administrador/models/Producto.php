<?php
class ProductoModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($filtros = []) {
        $where_conditions = [];
        $params = [];

        // Filtro por categoría
        if (!empty($filtros['categoria_id']) && $filtros['categoria_id'] !== 'todos') {
            $where_conditions[] = 'p.categoria_id = ?';
            $params[] = $filtros['categoria_id'];
        }

        // Búsqueda por ID o nombre
        if (!empty($filtros['buscar'])) {
            $where_conditions[] = '(p.nombre LIKE ? OR p.id = ?)';
            $params[] = "%{$filtros['buscar']}";
            $params[] = $filtros['buscar'];
        }

        $sql_where = '';
        if (!empty($where_conditions)) {
            $sql_where = 'WHERE ' . implode(' AND ', $where_conditions);
        }

        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                {$sql_where}
                ORDER BY p.fecha_creacion DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getCategorias() {
        $stmt = $this->pdo->query("SELECT * FROM categorias ORDER BY nombre");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function toggleStatus($id, $field) {
        // Validar que el campo sea uno de los permitidos para evitar inyección SQL
        if (!in_array($field, ['activo', 'destacado'])) {
            return false;
        }

        $stmt_current = $this->pdo->prepare("SELECT $field FROM productos WHERE id = ?");
        $stmt_current->execute([$id]);
        $estado_actual = $stmt_current->fetchColumn();

        if ($estado_actual === false) {
            return false; // No se encontró el producto
        }

        $nuevo_estado = ($estado_actual == 1) ? 0 : 1;
        $stmt_update = $this->pdo->prepare("UPDATE productos SET $field = ? WHERE id = ?");
        return $stmt_update->execute([$nuevo_estado, $id]);
    }

    public function add($data) {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, categoria_id, stock, activo, destacado, imagen, fecha_creacion) 
                VALUES (:nombre, :descripcion, :precio, :categoria_id, :stock, :activo, :destacado, :imagen, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':categoria_id' => $data['categoria_id'],
            ':stock' => $data['stock'],
            ':activo' => $data['activo'],
            ':destacado' => $data['destacado'],
            ':imagen' => $data['imagen']
        ]);
    }

    public function update($id, $data) {
        $sql = "UPDATE productos SET 
                    nombre = :nombre, 
                    descripcion = :descripcion, 
                    precio = :precio, 
                    categoria_id = :categoria_id, 
                    stock = :stock, 
                    activo = :activo, 
                    destacado = :destacado,
                    imagen = :imagen 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':categoria_id' => $data['categoria_id'],
            ':stock' => $data['stock'],
            ':activo' => $data['activo'],
            ':destacado' => $data['destacado'],
            ':imagen' => $data['imagen'],
            ':id' => $id
        ]);
    }
}
?>

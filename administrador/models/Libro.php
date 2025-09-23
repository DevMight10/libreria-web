<?php
class LibroModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll($filtros = [])
    {
        $where_conditions = [];
        $params = [];
        $limit_sql = '';

        if (isset($filtros['activo'])) {
            $where_conditions[] = 'p.activo = ?';
            $params[] = $filtros['activo'];
        }

        if (isset($filtros['destacado'])) {
            $where_conditions[] = 'p.destacado = ?';
            $params[] = $filtros['destacado'];
        }

        if (!empty($filtros['genero_id']) && $filtros['genero_id'] !== 'todos') {
            $where_conditions[] = 'p.genero_id = ?';
            $params[] = $filtros['genero_id'];
        }

        if (!empty($filtros['buscar'])) {
            $where_conditions[] = '(LOWER(p.nombre) LIKE ? OR LOWER(p.autor) LIKE ?)';
            $search_param = "%" . strtolower($filtros['buscar']) . "%";
            $params[] = $search_param;
            $params[] = $search_param;
        }

        $sql_where = '';
        if (!empty($where_conditions)) {
            $sql_where = 'WHERE ' . implode(' AND ', $where_conditions);
        }

        if (isset($filtros['limit'])) {
            $limit_sql = 'LIMIT ' . (int)$filtros['limit'];
        }

        $sql = "SELECT p.*, c.nombre as genero_nombre
                FROM libros p
                LEFT JOIN generos c ON p.genero_id = c.id
                {$sql_where}
                ORDER BY p.fecha_creacion DESC
                {$limit_sql}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getGeneros()
    {
        $stmt = $this->pdo->query("SELECT * FROM generos ORDER BY nombre");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $sql = "SELECT p.*, c.nombre as genero_nombre 
                FROM libros p
                LEFT JOIN generos c ON p.genero_id = c.id
                WHERE p.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function toggleStatus($id, $field)
    {
        if (!in_array($field, ['activo', 'destacado'])) {
            return false;
        }

        $stmt_current = $this->pdo->prepare("SELECT $field FROM libros WHERE id = ?");
        $stmt_current->execute([$id]);
        $estado_actual = $stmt_current->fetchColumn();

        if ($estado_actual === false) {
            return false;
        }

        $nuevo_estado = ($estado_actual == 1) ? 0 : 1;
        $stmt_update = $this->pdo->prepare("UPDATE libros SET $field = ? WHERE id = ?");
        return $stmt_update->execute([$nuevo_estado, $id]);
    }

    public function add($data)
    {
        $sql = "INSERT INTO libros (nombre, autor, descripcion, precio, genero_id, stock, activo, destacado, imagen, fecha_creacion) 
                VALUES (:nombre, :autor, :descripcion, :precio, :genero_id, :stock, :activo, :destacado, :imagen, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':autor' => $data['autor'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':genero_id' => $data['genero_id'],
            ':stock' => $data['stock'],
            ':activo' => $data['activo'],
            ':destacado' => $data['destacado'],
            ':imagen' => $data['imagen']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE libros SET 
                    nombre = :nombre, 
                    autor = :autor, 
                    descripcion = :descripcion, 
                    precio = :precio, 
                    genero_id = :genero_id, 
                    stock = :stock, 
                    activo = :activo, 
                    destacado = :destacado,
                    imagen = :imagen 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':autor' => $data['autor'],
            ':descripcion' => $data['descripcion'],
            ':precio' => $data['precio'],
            ':genero_id' => $data['genero_id'],
            ':stock' => $data['stock'],
            ':activo' => $data['activo'],
            ':destacado' => $data['destacado'],
            ':imagen' => $data['imagen'],
            ':id' => $id
        ]);
    }
}

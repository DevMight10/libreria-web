USE libreria_adrimarth_db;

START TRANSACTION;

-- Se usará una imagen de placeholder para todos los libros de ejemplo
SET @img := 'libro.jpeg';

INSERT INTO libros
  (nombre, autor, descripcion, precio, genero_id, imagen, stock, activo, destacado)
VALUES
-- 1) Biografías (genero_id 1)
('Steve Jobs', 'Walter Isaacson', 'La biografía definitiva del fundador de Apple, escrita por Walter Isaacson.', 189.90, 1, @img, 25, 1, 1),
('Becoming', 'Michelle Obama', 'Las memorias de la ex primera dama de los Estados Unidos, Michelle Obama.', 150.00, 1, @img, 30, 1, 0),

-- 2) No Ficción (genero_id 2)
('Sapiens: De animales a dioses', 'Yuval Noah Harari', 'Una breve historia de la humanidad por Yuval Noah Harari.', 165.50, 2, @img, 40, 1, 1),
('Hábitos Atómicos', 'James Clear', 'Un método probado para construir buenos hábitos y romper los malos por James Clear.', 130.00, 2, @img, 50, 1, 0),

-- 3) Misterio (genero_id 3)
('La chica del tren', 'Paula Hawkins', 'Un thriller psicológico de Paula Hawkins que te atrapará hasta el final.', 99.00, 3, @img, 35, 1, 1),
('El código Da Vinci', 'Dan Brown', 'El famoso thriller de misterio de Dan Brown protagonizado por Robert Langdon.', 110.00, 3, @img, 45, 1, 0),

-- 4) Ciencia Ficción (genero_id 4)
('Dune', 'Frank Herbert', 'La galardonada novela de ciencia ficción de Frank Herbert, un clásico del género.', 175.00, 4, @img, 28, 1, 1),
('Fahrenheit 451', 'Ray Bradbury', 'Una distopía clásica de Ray Bradbury sobre una sociedad donde los libros están prohibidos.', 95.00, 4, @img, 33, 1, 0),

-- 5) Infantil (genero_id 5)
('El Principito', 'Antoine de Saint-Exupéry', 'Un cuento poético y filosófico de Antoine de Saint-Exupéry, amado por niños y adultos.', 75.00, 5, @img, 60, 1, 1),
('Donde viven los monstruos', 'Maurice Sendak', 'Un clásico libro ilustrado de Maurice Sendak sobre la imaginación y el manejo de la ira.', 85.00, 5, @img, 55, 1, 0);

COMMIT;
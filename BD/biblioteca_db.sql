CREATE TABLE IF NOT EXISTS configuracion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_sistema VARCHAR(100) NOT NULL,
    imagen_sistema VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO configuracion (id, nombre_sistema, imagen_sistema) 
VALUES (1, 'Biblioteca Digital y Física', 'assets/img/logo_default.png')
ON DUPLICATE KEY UPDATE id=id;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'staff') NOT NULL,
    estado TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
--
UPDATE usuarios SET password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm' WHERE usuario = 'admin';
--
--

-- Usuario admin por defecto (Contraseña: 123456)
INSERT INTO usuarios (nombre, usuario, password, rol) 
VALUES ('Administrador', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE usuario=usuario;

-- Usuario staff por defecto (Contraseña: 123456)
INSERT INTO usuarios (nombre, usuario, password, rol) 
VALUES ('Bibliotecario Staff', 'staff', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff')
ON DUPLICATE KEY UPDATE usuario=usuario;

CREATE TABLE IF NOT EXISTS estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cedula VARCHAR(20) NOT NULL UNIQUE,
    nombre_completo VARCHAR(150) NOT NULL,
    carrera_grado VARCHAR(100) NOT NULL,
    telefono VARCHAR(30),
    estado TINYINT DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    titulo VARCHAR(150) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    id_categoria INT NOT NULL,
    tipo ENUM('fisico', 'digital') NOT NULL,
    stock INT DEFAULT 0,
    archivo_pdf VARCHAR(255),
    estado VARCHAR(20) DEFAULT 'activo',
    FOREIGN KEY (id_categoria) REFERENCES categorias(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_estudiante INT NOT NULL,
    id_libro INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_estimada DATE NOT NULL,
    fecha_devolucion_real DATE,
    estado VARCHAR(20) DEFAULT 'prestado',
    FOREIGN KEY (id_estudiante) REFERENCES estudiantes(id),
    FOREIGN KEY (id_libro) REFERENCES libros(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE configuracion ADD COLUMN IF NOT EXISTS rif VARCHAR(50) DEFAULT '';
ALTER TABLE configuracion ADD COLUMN IF NOT EXISTS direccion TEXT DEFAULT '';

ALTER TABLE estudiantes ADD COLUMN IF NOT EXISTS edad INT DEFAULT 0;
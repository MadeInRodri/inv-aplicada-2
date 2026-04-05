CREATE DATABASE IF NOT EXISTS empresa_db;
USE empresa_db;

-- Tabla para el formulario de contacto (CREATE)
CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    asunto VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla para operaciones CRUD completas (CREATE, READ, UPDATE, DELETE)
CREATE TABLE servicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

-- Insertar datos estáticos de prueba
INSERT INTO servicios (nombre, descripcion) VALUES 
('Consultoría TI', 'Asesoramiento en infraestructura y desarrollo.'),
('Desarrollo Web', 'Creación de sitios web dinámicos asíncronos.');
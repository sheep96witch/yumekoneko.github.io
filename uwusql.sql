-- Crear base de datos y usarla
CREATE DATABASE Biblioteca;
USE Biblioteca;

-- Tabla de Administradores
CREATE TABLE Administradores (
    ID_Trabajador INT AUTO_INCREMENT PRIMARY KEY,
    Nombre VARCHAR(100) NOT NULL,
    Apellidos VARCHAR(100) NOT NULL,
    Telefono VARCHAR(15) NOT NULL,
    Correo VARCHAR(100) NOT NULL UNIQUE,
    Contraseña VARCHAR(255) NOT NULL,
    Rol ENUM('admin', 'superadmin') DEFAULT 'admin',
    Fecha_Creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Tabla de Usuarios
CREATE TABLE Usuarios (
    ID_Usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    Nombre varchar(100) NOT NULL,
    Usuario VARCHAR(70) NOT NULL,
    Telefono VARCHAR(20),
    Password VARCHAR(90) NOT NULL,
    Correo VARCHAR(90) NOT NULL,
    UNIQUE (Nombre, Correo, Telefono) 

);
ALTER TABLE contactos ADD COLUMN respuesta TEXT NULL AFTER mensaje;

-- Tabla de Libros
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    autor VARCHAR(50),
    genero VARCHAR(50),
    es_manga BOOLEAN DEFAULT 0,
    precio DECIMAL(10,2),
    cantidad_existencia INT,
    UNIQUE (id, Titulo)
);

-- Tabla de Contactos (para almacenar mensajes de los usuarios)
CREATE TABLE contactos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    mensaje TEXT NOT NULL,
        UNIQUE (id, nombre, email)

);

-- Insertar algunos libros como ejemplo
INSERT INTO libros (titulo, autor, genero, es_manga, precio, cantidad_existencia)
VALUES
    ('Metro 2033', 'Dmitry Glukhovsky', 'Ciencia ficción', 0, 12.99, 15),
    ('It', 'Stephen King', 'Terror', 0, 19.99, 10),
    ('El Señor de los Anillos', 'J.R.R. Tolkien', 'Fantasía', 0, 25.99, 8),
    ('One Piece', 'Eiichiro Oda', 'Shonen', 1, 9.99, 20),
    ('Attack on Titan', 'Hajime Isayama', 'Shonen', 1, 11.99, 12);

-- Insertar algunos administradores como ejemplo
INSERT INTO Administradores (Nombre, Apellidos, Telefono, Salario, Contraseña, Correo)
VALUES
    ('TopGamerAngel', 'Guzman Lopez', '1234567890', 25000, SHA1('Hu-Tao'), 'angel@example.com'),
    ('Sheep', 'Valdez', '9876543210', 30000, SHA1('12345'), 'sheep@example.com'),
    ('Tommy', 'Lopez', '5555555555', 28000, SHA1('Huevos1'), 'tommy@example.com');

-- Insertar algunos usuarios como ejemplo
INSERT INTO Usuarios (Nombre, Telefono, Password, Correo)
VALUES
   ('Finn', '1234567890', SHA1('Finn1234'), 'finn@example.com'),
    ('Jake', '9876543', SHA1('Jake1234'), 'jake@example.com'),
    ('BMO', '5555555555', SHA1('BMOrocks'), 'bmo@example.com');
    
    
    
    ALTER TABLE Usuarios MODIFY COLUMN Usuario VARCHAR(70) NOT NULL DEFAULT 'usuario_default';

    ALTER TABLE Administradores ADD COLUMN Salario DECIMAL(10, 2);

    drop table Administradores;
-- Deshabilitar las verificaciones de claves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar la tabla
DROP TABLE Administradores;

-- Habilitar nuevamente las verificaciones de claves foráneas
SET FOREIGN_KEY_CHECKS = 1;


    ALTER TABLE Administradores ADD COLUMN Telefono VARCHAR(20);

    SELECT * FROM Usuarios WHERE ID_Administrador = (ID del Administrador);

    
    -- add descripcion
    DESCRIBE libros;

    alter table libros add column descripcion TEXT AFTER AUTOR;
-- Consultas de ejemplo para verificar los registros
SELECT * FROM Usuarios;
SELECT * FROM Administradores;
SELECT * FROM libros;
SELECT *FROM contactos;
-- Para eliminar las tablas de Usuarios y Administradores (si es necesario)
-- DROP TABLE usuarios;
-- DROP TABLE Administradores;
DELETE FROM usuarios;
REPAIR TABLE libros;
drop table libros;
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    descripcion TEXT NOT NULL,
    es_manga BOOLEAN NOT NULL,
    imagen VARCHAR(255) NOT NULL
);

SHOW PROCESSLIST;
SET foreign_key_checks = 0;
DELETE FROM usuarios WHERE id = 1;
SET foreign_key_checks = 1;
SET SQL_SAFE_UPDATES = 0;
ALTER TABLE usuarios AUTO_INCREMENT = 1;
TRUNCATE TABLE contactos;
-- aja
ALTER TABLE Administradores ADD CONSTRAINT unique_correo UNIQUE (Correo);
ALTER TABLE Usuarios ADD CONSTRAINT unique_email UNIQUE (Email);
DESCRIBE Usuarios;

ALTER TABLE Usuarios
DROP COLUMN Nombre,
DROP COLUMN Numero;
ALTER TABLE Usuarios 
MODIFY COLUMN Usuario VARCHAR(70) NOT NULL DEFAULT 'usuario_default';
SELECT * FROM Usuarios 
WHERE Nombre = 'pepe' 
AND Correo = 'user1@example.com' 
AND Telefono = '1234567890';
-- Eliminar todos los registros
DELETE FROM Usuarios;
DROP TABLE Usuarios; 
DELETE FROM Administradores;

-- Restablecer el contador de AUTO_INCREMENT
ALTER TABLE Usuarios AUTO_INCREMENT = 1;
ALTER TABLE Administradores AUTO_INCREMENT = 1;

-- add

ALTER TABLE Usuarios ADD COLUMN ID_Administrador INT;
ALTER TABLE Usuarios ADD CONSTRAINT fk_admin FOREIGN KEY (ID_Administrador) REFERENCES Administradores(ID_Trabajador);

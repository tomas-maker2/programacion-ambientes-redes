-- Base de datos del TP Final.
-- Si se vuelve a correr entero, las tablas no se recrean (ya existen),
-- pero los INSERT sí se repiten y duplican los datos de ejemplo.
-- Para resetear todo: DROP DATABASE tp_final; y correr este archivo de nuevo.

CREATE DATABASE IF NOT EXISTS tp_final CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tp_final;

CREATE TABLE IF NOT EXISTS condiciones_iva (
    id     INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clientes (
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    codCliente            VARCHAR(20) NOT NULL,
    razonSocial           VARCHAR(100) NOT NULL,
    cuit                  VARCHAR(20) NOT NULL,
    condicionIva_id       INT NOT NULL,
    fechaInicio           DATE NOT NULL,
    saldoCuentaCorriente  DECIMAL(12,2) NOT NULL DEFAULT 0,
    ultimoBalance         DECIMAL(12,2) NOT NULL DEFAULT 0,
    archivo               LONGBLOB NULL,          -- PDF o imagen adjunta al cliente
    FOREIGN KEY (condicionIva_id) REFERENCES condiciones_iva(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuarios (
    idUsuario         INT AUTO_INCREMENT PRIMARY KEY,
    login             VARCHAR(50) NOT NULL,
    apellido          VARCHAR(100) NOT NULL,
    nombres           VARCHAR(100) NOT NULL,
    contraseña        VARCHAR(64) NOT NULL,   -- hash SHA-256: siempre 64 caracteres hexadecimales
    cantidadSesiones  INT(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos de ejemplo, los mismos que tenía ESPECIALES/condicionesIva.json
INSERT INTO condiciones_iva (id, nombre) VALUES
    (1, 'IVA General (21%)'),
    (2, 'IVA Reducido (10.5%)'),
    (3, 'IVA Aumentado (27%)'),
    (4, 'Exento');

-- Datos de ejemplo, los mismos que tenía ESPECIALES/clientes.json
INSERT INTO clientes (codCliente, razonSocial, cuit, condicionIva_id, fechaInicio, saldoCuentaCorriente, ultimoBalance) VALUES
    ('C001', 'Distribuidora del Sur SA', '30-71234567-9', 1, '2021-03-15', 45000, 128000),
    ('C002', 'Comercial Bolotnicoff SRL', '30-70123456-4', 2, '2019-07-01', 0, 76500),
    ('C003', 'Juan Pérez', '20-25874196-3', 4, '2022-11-20', -12300, 5400),
    ('C004', 'Importadora Norte SA', '30-68541239-7', 3, '2023-01-10', 89000, 210000);

-- Usuario de prueba para el login (login: admin / contraseña: admin123)
INSERT INTO usuarios (login, apellido, nombres, contraseña) VALUES
    ('admin', 'Administrador', 'Sistema', SHA2('admin123', 256));

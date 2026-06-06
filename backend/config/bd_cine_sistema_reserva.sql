DROP DATABASE IF EXISTS bd_sistema_reserva_cine;
CREATE DATABASE bd_sistema_reserva_cine;
USE bd_sistema_reserva_cine;

-- =========================
-- TABLAS 
-- =========================

CREATE TABLE genero (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL
);

CREATE TABLE rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(20) NOT NULL UNIQUE,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    descripcion VARCHAR(50) DEFAULT '-'
);

CREATE TABLE sala (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT UNIQUE NOT NULL,
    filas INT NOT NULL,
    butacas_por_fila  INT NOT NULL,
    activa BOOLEAN DEFAULT TRUE
);

CREATE TABLE tipo_producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(100)
);

CREATE TABLE estado_reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(28) NOT NULL
);

CREATE TABLE estado_funcion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL
);

-- =========================
-- PELICULA
-- =========================

CREATE TABLE pelicula (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    director VARCHAR(100) NOT NULL,
    anio INT NOT NULL,
    duracion INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    disponible BOOLEAN DEFAULT FALSE,
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 🖼 IMÁGENES DE PELICULA
CREATE TABLE pelicula_imagen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelicula_id INT NOT NULL,
    tipo ENUM('poster','banner') NOT NULL,
    url VARCHAR(255) NOT NULL,
    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id) ON DELETE CASCADE
);

-- =========================
-- USUARIOS
-- =========================

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rol_id INT NOT NULL,
    nombre VARCHAR(50),
    email VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    ciudad VARCHAR(50),
    provincia VARCHAR(50),
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE CASCADE
);

-- =========================
-- PRODUCTOS
-- =========================

CREATE TABLE producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_id INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    comentario VARCHAR(400),
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tipo_id) REFERENCES tipo_producto(id) ON DELETE CASCADE
);

-- =========================
-- RELACIONES
-- =========================

CREATE TABLE pelicula_genero (
    pelicula_id INT NOT NULL,
    genero_id INT NOT NULL,
    PRIMARY KEY (pelicula_id, genero_id),
    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id) ON DELETE CASCADE,
    FOREIGN KEY (genero_id) REFERENCES genero(id) ON DELETE CASCADE
);

CREATE TABLE butaca (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sala_id INT NOT NULL,
    fila INT NOT NULL,
    numero INT NOT NULL,
    
    UNIQUE (sala_id, fila, numero),

    FOREIGN KEY (sala_id) REFERENCES sala(id) ON DELETE CASCADE
);

-- =========================
-- PROGRAMACIÓN (PRORAMACION DE FUNCIONES)
-- =========================

CREATE TABLE programacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelicula_id INT NOT NULL,
    sala_id INT NOT NULL,
    hora TIME NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado BOOLEAN DEFAULT TRUE,

    UNIQUE (sala_id, fecha_inicio, hora),

    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id),
    FOREIGN KEY (sala_id) REFERENCES sala(id)
);

-- =========================
-- FUNCIONES (PROYECCIONES)
-- =========================
CREATE TABLE funcion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    programacion_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    estado_id INT NOT NULL,
    
    UNIQUE(programacion_id, fecha_hora),

    FOREIGN KEY (programacion_id) REFERENCES programacion(id),
    FOREIGN KEY (estado_id) REFERENCES estado_funcion(id)
);

-- =========================
-- RESERVAS
-- =========================

CREATE TABLE reserva (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NULL,
    nombre_cliente VARCHAR(100),
    email_cliente VARCHAR(100) NOT NULL,
    funcion_id INT NOT NULL,
    estado_id INT NOT NULL,
    fecha_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (funcion_id) REFERENCES funcion(id) ON DELETE CASCADE,
    FOREIGN KEY (estado_id) REFERENCES estado_reserva(id) ON DELETE CASCADE
);

CREATE TABLE reserva_butaca (
    butaca_id INT NOT NULL,
    reserva_id INT NOT NULL,
    precio DECIMAL(10,2),
    PRIMARY KEY (reserva_id, butaca_id),

    FOREIGN KEY (butaca_id) REFERENCES butaca(id) ON DELETE CASCADE,
    FOREIGN KEY (reserva_id) REFERENCES reserva(id) ON DELETE CASCADE
);

CREATE TABLE reserva_producto (
    reserva_id INT NOT NULL,
    producto_id INT NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (reserva_id, producto_id),

    FOREIGN KEY (reserva_id) REFERENCES reserva(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES producto(id) ON DELETE CASCADE
);



-- --------------------------------------------------------
-- INSERTAR DATOS A LA BD ---------------------------------
-- --------------------------------------------------------

-- -------------------------------
-- GENERO
-- -------------------------------
INSERT INTO genero (nombre) VALUES 
('Acción'),
('Comedia'),
('Drama'),
('Terror'),
('Ciencia Ficción');

-- -------------------------------
-- ROL
-- -------------------------------
INSERT INTO rol (tipo, descripcion) VALUES
('administrador', 'Administrador del sistema'),
('cliente', 'Usuario que compra entradas');

-- -------------------------------
-- SALA
-- -------------------------------
INSERT INTO sala (numero, filas, butacas_por_fila, activa) VALUES
(1, 5, 10, TRUE),
(2, 5, 10, TRUE);

-- -------------------------------
-- TIPO PRODUCTO
-- -------------------------------
INSERT INTO tipo_producto (nombre, descripcion) VALUES
('Snack', 'Comida para el cine'),
('Bebida', 'Refrescos y bebidas'),
('Combo', 'Combinación de snacks y bebida');

-- -------------------------------
-- ESTADO RESERVA
-- -------------------------------
INSERT INTO estado_reserva (nombre) VALUES
('pendiente'),
('pagado'),
('cancelado');

-- -----------------------
-- ESTADO FUNCION
INSERT INTO estado_funcion (nombre) VALUES
('activa'),
('cancelada'),
('finalizada');

-- -------------------------------
-- PELICULA
-- -------------------------------
INSERT INTO pelicula (titulo, descripcion, director, anio, duracion, precio, disponible) VALUES
('Inception', 'Un ladrón que roba secretos a través de los sueños.', 'Christopher Nolan', 2010, 148, 8.00, TRUE),
('Interstellar', 'Viaje espacial para salvar a la humanidad.', 'Christopher Nolan', 2014, 169, 8.00, TRUE),
('Joker', 'Historia de origen del villano Joker.', 'Todd Phillips', 2019, 122, 8.00, TRUE),
('Titanic', 'Romance a bordo del famoso transatlántico.', 'James Cameron', 1997, 195, 8.00, TRUE),
('Gladiator', 'Un general romano busca venganza.', 'Ridley Scott', 2000, 155, 8.00, TRUE),
('Avatar', 'Humanos en Pandora y conflictos con los nativos.', 'James Cameron', 2009, 162, 8.00, TRUE);

-- Peliculas no disponibles
INSERT INTO pelicula (titulo, descripcion, director, anio, duracion, precio, disponible) VALUES
('La Maldicion de Green House', 'Misterios en una antigua mansión.', 'Denis Villeneuve', 2026, 155, 8.00, FALSE),
('Fin del Amanecer', 'Héroes contra un cataclismo.', 'Nia DaCosta', 2026, 120, 8.00, FALSE),
('Aprueba de Balas', 'Ciencia y ética en conflicto.', 'Christopher Nolan', 2026, 180, 8.00, FALSE),
('Caida en Picada', 'Aventura caótica de amigos.', 'Greta Gerwig', 2026, 115, 8.00, FALSE),
('Hombre sin rostro', 'Distopía y rebelión.', 'Francis Lawrence', 2026, 140, 8.00, FALSE),
('Sin Rumbo', 'Viaje de supervivencia.', 'Francis Lawrence', 2026, 140, 8.00, FALSE);

-- ----------------------------------------------------
-- IMAGENES DE PELICULAS ------------------------------
-- ----------------------------------------------------
INSERT INTO pelicula_imagen (pelicula_id, tipo, url) VALUES
-- Inception
(1, 'poster', '/backend/uploads/inception_poster.png'),
(1, 'banner', '/backend/uploads/inception_banner.jpg'),

-- Interstellar
(2, 'poster', '/backend/uploads/interestelar_poster.png'),
(2, 'banner', '/backend/uploads/interestelar_banner.jpg'),

-- Joker
(3, 'poster', '/backend/uploads/joker_poster.png'),
(3, 'banner', '/backend/uploads/jocker_banner.png'),

-- Titanic
(4, 'poster', '/backend/uploads/titanic_poster.png'),
(4, 'banner', '/backend/uploads/titanic_banner.jpg'),

-- Gladiator
(5, 'poster', '/backend/uploads/gladiador_poster.png'),
(5, 'banner', '/backend/uploads/gladiator_banner.jpg'),

-- Avatar
(6, 'poster', '/backend/uploads/avatar_poster.png'),
(6, 'banner', '/backend/uploads/avatar_banner.jpg');

-- -------------------------------
-- PELICULA-GENERO
-- -------------------------------
INSERT INTO pelicula_genero (pelicula_id, genero_id) VALUES
(3, 3), -- Joker -> Drama
(4, 1), -- Titanic -> Acción (aunque es más drama/romance, puedes asignar Drama también)
(4, 3), -- Titanic -> Drama
(5, 1), -- Gladiator -> Acción
(5, 3), -- Gladiator -> Drama
(6, 5), -- Avatar -> Ciencia Ficción
(7, 4), -- La Maldicion de Green House: Terror
(8, 1), -- Fin del Amanecer: Acción
(9, 1), -- Aprueba de Balas: Accion
(10, 2), -- Caida en Picada: Accion
(11, 5), -- Hombre sin rostro: Ciencia Ficcion
(12, 3); -- Sin Rumbo: Drama

-- -------------------------------
-- Programaci
-- -------------------------------
INSERT INTO programacion
(id, pelicula_id, sala_id, hora, fecha_inicio, fecha_fin, estado)
VALUES
(1, 3, 2, '20:00:00', '2026-02-22', '2026-02-22', TRUE), -- Joker
(2, 4, 1, '18:30:00', '2026-02-22', '2026-02-22', TRUE), -- Titanic
(3, 5, 2, '21:00:00', '2026-02-23', '2026-02-23', TRUE), -- Gladiator
(4, 6, 2, '19:00:00', '2026-02-23', '2026-02-23', TRUE), -- Avatar
(5, 1, 1, '22:00:00', '2026-02-23', '2026-02-23', TRUE), -- Inception
(6, 2, 1, '16:00:00', '2026-02-24', '2026-02-24', TRUE); -- Interstellar


-- Funciones (proyecciones)
-- -------------------------------
INSERT INTO funcion
(id, programacion_id, fecha_hora, estado_id)
VALUES
(1, 1, '2026-02-22 20:00:00', 1),
(2, 2, '2026-02-22 18:30:00', 1),
(3, 3, '2026-02-23 21:00:00', 1),
(4, 4, '2026-02-23 19:00:00', 1),
(5, 5, '2026-02-23 22:00:00', 1),
(6, 6, '2026-02-24 16:00:00', 1);


-- Insertar Butacas en sala 1
INSERT INTO butaca (sala_id, fila, numero) VALUES
(1,1,1),(1,1,2),(1,1,3),(1,1,4),(1,1,5),(1,1,6),(1,1,7),(1,1,8),(1,1,9),(1,1,10),
(1,2,1),(1,2,2),(1,2,3),(1,2,4),(1,2,5),(1,2,6),(1,2,7),(1,2,8),(1,2,9),(1,2,10),
(1,3,1),(1,3,2),(1,3,3),(1,3,4),(1,3,5),(1,3,6),(1,3,7),(1,3,8),(1,3,9),(1,3,10),
(1,4,1),(1,4,2),(1,4,3),(1,4,4),(1,4,5),(1,4,6),(1,4,7),(1,4,8),(1,4,9),(1,4,10),
(1,5,1),(1,5,2),(1,5,3),(1,5,4),(1,5,5),(1,5,6),(1,5,7),(1,5,8),(1,5,9),(1,5,10);

-- Insertar butacas en sala 2
INSERT INTO butaca (sala_id, fila, numero) VALUES
(2,1,1),(2,1,2),(2,1,3),(2,1,4),(2,1,5),(2,1,6),(2,1,7),(2,1,8),(2,1,9),(2,1,10),
(2,2,1),(2,2,2),(2,2,3),(2,2,4),(2,2,5),(2,2,6),(2,2,7),(2,2,8),(2,2,9),(2,2,10),
(2,3,1),(2,3,2),(2,3,3),(2,3,4),(2,3,5),(2,3,6),(2,3,7),(2,3,8),(2,3,9),(2,3,10),
(2,4,1),(2,4,2),(2,4,3),(2,4,4),(2,4,5),(2,4,6),(2,4,7),(2,4,8),(2,4,9),(2,4,10),
(2,5,1),(2,5,2),(2,5,3),(2,5,4),(2,5,5),(2,5,6),(2,5,7),(2,5,8),(2,5,9),(2,5,10);

-- Insertar producto
INSERT INTO producto (tipo_id, nombre, precio, comentario) VALUES
(1, 'Palomitas', 4.50, 'Tamaño grande'),
(2, 'Refresco', 2.50, '500ml');

INSERT INTO usuario (rol_id, nombre, email, contrasena)
VALUES
(2, 'Juan Perez', 'juan@mail.com', 'juan'),
(2, 'Maria Lopez', 'maria@mail.com', 'maria'),
(2, 'Carlos Solaz', 'carlos@mail.com', 'carlos'),
(2, 'Sara Vega', 'sara@mail.com', 'sara'),
(1, 'admin', 'admin@admin.com', 'admin');

-- Insertar reservas de la película
INSERT INTO reserva (usuario_id, nombre_cliente, email_cliente, funcion_id, estado_id, total)
VALUES
(1, 'Juan Perez', 'juan@mail.com', 1, 2, 8.50),
(2, 'Maria Lopez', 'maria@mail.com', 3, 1, 7.50);

-- Insertar reserva butaca
INSERT INTO reserva_butaca (butaca_id, reserva_id, precio) VALUES
(1, 1, 8.50),
(51, 2, 7.50);

-- Insertar reserva producto
INSERT INTO reserva_producto (reserva_id, producto_id, precio_total) VALUES
(1, 1, 4.50),
(2, 2, 2.50);

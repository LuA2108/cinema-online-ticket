DROP DATABASE IF EXISTS bd_sistema_reserva_cine;
CREATE DATABASE IF NOT EXISTS bd_sistema_reserva_cine;
USE bd_sistema_reserva_cine;

-- Tablas independientes
-- GENERO --------------------------
DROP table if exists genero;
CREATE TABLE if not exists genero (
	id INT PRIMARY KEY auto_increment,
    nombre VARCHAR(20)
);

-- ROL --------------------------
DROP TABLE IF EXISTS rol;
CREATE TABLE IF NOT EXISTS rol (
	id INT AUTO_INCREMENT PRIMARY KEY,
	tipo VARCHAR(20) NOT NULL,
    descripcion VARCHAR(50) DEFAULT '-'
);

-- SALA --------------------------
DROP table if exists sala;
CREATE TABLE if not exists sala (
	id INT auto_increment primary key,
    numero INT UNIQUE NOT NULL,
    capacidad INT NOT NULL
);

-- TIPO PRODUCTO ---------------------------
DROP TABLE if exists tipo;
CREATE TABLE if not exists tipo (
	id int auto_increment primary key,
	nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(100)
);

-- ESTADO RESERVA ---------------------------
DROP TABLE if exists estado_reserva;
CREATE TABLE if not exists estado_reserva (
	id int auto_increment primary key,
    nombre VARCHAR(28) NOT NULL
);

CREATE TABLE estado_funcion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL
);
-- -----------------------------------
-- Tablas principales ----------------

-- PELICULA --------------------------
DROP table if exists pelicula;
CREATE TABLE if not exists pelicula (
	id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descripcion TEXT,
    director VARCHAR(100) NOT NULL,
    anio INT NOT NULL,
    duracion INT NOT NULL, -- Minutos
    precio DECIMAL(10, 2) NOT NULL,
    disponible boolean DEFAULT FALSE,
    portada VARCHAR(255), -- URL
    create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha de creacion
);

-- USUARIO --------------------------
DROP table if exists usuario;
CREATE TABLE if not exists usuario (
	id INT auto_increment primary key,
    rol_id INT NOT NULL,
    nombre varchar(50),
    email varchar(50) NOT NULL UNIQUE,
    contrasena varchar(255) NOT NULL,
    ciudad varchar(50),
    provincia varchar(50),
    create_time timestamp DEFAULT current_timestamp,
    
    FOREIGN KEY (rol_id) REFERENCES rol(id) ON DELETE CASCADE
);

-- PRODUCTO ---------------------------
DROP TABLE if exists producto;
CREATE TABLE if not exists producto (
	id int auto_increment primary key,
    tipo_id INT NOT NULL,
	nombre VARCHAR(50) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    comentario VARCHAR(400) NOT NULL,
    create_time timestamp DEFAULT current_timestamp,
    
    foreign key (tipo_id) references tipo(id) ON DELETE CASCADE
);

-- TABLAS DEPENDIENTES

-- PELICULA-GENERO (Muchos a muchos) --------------------------
DROP TABLE if exists pelicula_genero;
CREATE TABLE if not exists pelicula_genero (
    pelicula_id INT NOT NULL,
    genero_id INT NOT NULL,
    PRIMARY KEY (pelicula_id, genero_id),
    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id) ON DELETE CASCADE,
    FOREIGN KEY (genero_id) REFERENCES genero(id) ON DELETE CASCADE
);

-- BUTACA (depende de sala) ------------------
DROP table if exists butaca;
CREATE TABLE if not exists butaca (
	id INT auto_increment primary key,
    sala_id INT NOT NULL,
    numero INT NOT NULL,
    fila INT NOT NULL,

    UNIQUE (sala_id, fila, numero),
    FOREIGN KEY (sala_id) REFERENCES sala(id) ON DELETE CASCADE
);

-- FUNCION ( Proyeccion: Pelicula + sala + hora )----------------   
DROP table if exists funcion;
CREATE TABLE if not exists funcion (
	id INT auto_increment primary key,
    pelicula_id INT NOT NULL,
    sala_id INT NOT NULL,
    hora TIME NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado_id INT NOT NULL,
    
    UNIQUE (sala_id, fecha_inicio, hora),
    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id),
    FOREIGN KEY (sala_id) REFERENCES sala(id),
    FOREIGN KEY (estado_id) REFERENCES estado_funcion(id)
);

-- RESERVA (Depende de Usuario, Funcion y estado) ---------------------------
DROP TABLE if exists reserva;
CREATE TABLE if not exists reserva (
	id int auto_increment primary key,
	usuario_id INT NOT NULL,
    funcion_id INT NOT NULL,
    estado_id INT NOT NULL,
    fecha_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    
    foreign key (usuario_id) references usuario(id) ON DELETE CASCADE,
    foreign key (funcion_id) references funcion(id) ON DELETE CASCADE,
    foreign key (estado_id) references estado_reserva(id) ON DELETE CASCADE
);

-- RESERVA-BUTACA (Detalle de la reserva: Qué asientos son) ----------
DROP table if exists reserva_butaca;
CREATE TABLE if not exists reserva_butaca (
	butaca_id INT NOT NULL,
    reserva_id INT NOT NULL,
    funcion_id INT NOT NULL,
    precio DECIMAL(10,2),
    
    PRIMARY KEY (funcion_id, butaca_id),
    FOREIGN KEY (butaca_id) REFERENCES butaca(id) ON DELETE CASCADE,
    FOREIGN KEY (reserva_id) REFERENCES reserva(id) ON DELETE CASCADE,
    FOREIGN KEY (funcion_id) REFERENCES funcion(id) ON DELETE CASCADE
);

-- RESERVA-PRODUCTO (Si compran palomitas con la entrada) ---------------
DROP TABLE if exists reserva_producto;
CREATE TABLE if not exists reserva_producto (
	reserva_id INT NOT NULL,
    producto_id INT NOT NULL,
	precio_total DECIMAL(10,2) NOT NULL,
    
    PRIMARY KEY(reserva_id, producto_id),
    
    foreign key (reserva_id) references reserva(id) ON DELETE CASCADE,
    foreign key (producto_id) references producto(id) ON DELETE CASCADE
);

-- OPINION ---------------------------
DROP TABLE if exists opinion;
CREATE TABLE if not exists opinion (
	id int auto_increment primary key,
	pelicula_id INT NOT NULL,
    usuario_id INT NOT NULL,
    comentario VARCHAR(400) NOT NULL,
    create_time timestamp DEFAULT current_timestamp,
    
    foreign key (pelicula_id) references pelicula(id) ON DELETE CASCADE,
    foreign key (usuario_id) references usuario(id) ON DELETE CASCADE
);


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
('admin', 'Administrador del sistema'),
('cliente', 'Usuario que compra entradas'),
('empleado', 'Personal del cine');

-- -------------------------------
-- SALA
-- -------------------------------
INSERT INTO sala (numero, capacidad) VALUES
(1, 50),
(2, 50);

-- -------------------------------
-- TIPO PRODUCTO
-- -------------------------------
INSERT INTO tipo (nombre, descripcion) VALUES
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

-------------------------
-- ESTADO FUNCION
INSERT INTO estado_funcion (nombre) VALUES
('activa'),
('cancelada'),
('finalizada');

-- -------------------------------
-- PELICULA
-- -------------------------------
INSERT INTO pelicula (titulo, descripcion, director, anio, duracion, precio, disponible, portada) VALUES
('Inception', 'Un ladrón que roba secretos a través de los sueños.', 'Christopher Nolan', 2010, 148, 13.50, TRUE, 'https://example.com/inception.jpg'),
('Interstellar', 'Viaje espacial para salvar a la humanidad.', 'Christopher Nolan', 2014, 169, 14.00, TRUE, 'https://example.com/interstellar.jpg'),
('Joker', 'Historia de origen del villano Joker.', 'Todd Phillips', 2019, 122, 12.00, TRUE, 'https://example.com/joker.jpg'),
('Titanic', 'Romance a bordo del famoso transatlántico.', 'James Cameron', 1997, 195, 11.50, TRUE, 'https://example.com/titanic.jpg'),
('Gladiator', 'Un general romano busca venganza.', 'Ridley Scott', 2000, 155, 12.50, TRUE, 'https://example.com/gladiator.jpg'),
('Avatar', 'Humanos en Pandora y conflictos con los nativos.', 'James Cameron', 2009, 162, 14.00, TRUE, 'https://example.com/avatar.jpg');

-- -------------------------------
-- PELICULA-GENERO
-- -------------------------------
INSERT INTO pelicula_genero (pelicula_id, genero_id) VALUES
(3, 3), -- Joker -> Drama
(4, 1), -- Titanic -> Acción (aunque es más drama/romance, puedes asignar Drama también)
(4, 3), -- Titanic -> Drama
(5, 1), -- Gladiator -> Acción
(5, 3), -- Gladiator -> Drama
(6, 5); -- Avatar -> Ciencia Ficción

-- -------------------------------
-- FUNCION
-- -------------------------------
INSERT INTO funcion (pelicula_id, sala_id, hora, fecha_inicio, fecha_fin, estado_id) VALUES
(3, 2, '20:00:00', '2026-02-22', '2026-02-22', 1), -- Joker en sala 2
(4, 1, '18:30:00', '2026-02-22', '2026-02-22', 1), -- Titanic en sala 1
(5, 2, '21:00:00', '2026-02-23', '2026-02-23', 1), -- Gladiator en sala 2
(6, 2, '19:00:00', '2026-02-23', '2026-02-23', 1), -- Avatar en sala 2
(1, 1, '22:00:00', '2026-02-23', '2026-02-23', 1), -- Inception en sala 1
(2, 1, '16:00:00', '2026-02-24', '2026-02-24', 1); -- Interstellar en sala 1

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

-- Insertar pelicula genero
INSERT INTO pelicula_genero (pelicula_id, genero_id) VALUES
(1,5),(1,1),
(2,5),
(3,2);

-- Insertar producto
INSERT INTO producto (tipo_id, nombre, precio, comentario) VALUES
(1, 'Palomitas', 4.50, 'Tamaño grande'),
(2, 'Refresco', 2.50, '500ml');

-- Insertar Funcion
INSERT INTO funcion (pelicula_id, sala_id, hora, fecha_inicio, fecha_fin, estado_id) VALUES
(1, 1, '18:00:00', '2026-02-20', '2026-02-20', 1),
(2, 1, '21:00:00', '2026-02-20', '2026-02-20', 1),
(3, 2, '19:00:00', '2026-02-21', '2026-02-21', 1);

INSERT INTO usuario (rol_id, nombre, email, contrasena)
VALUES
(2, 'Juan Perez', 'juan@mail.com', 'juan'),
(2, 'Maria Lopez', 'maria@mail.com', 'maria'),
(2, 'Carlos Solaz', 'carlos@mail.com', 'carlos'),
(2, 'Sara Vega', 'sara@mail.com', 'sara');

-- Insertar reserva
INSERT INTO reserva (usuario_id, funcion_id, estado_id, total) VALUES
(2, 1, 2, 8.50),
(3, 3, 1, 7.50);

-- Insertar reserva butaca
INSERT INTO reserva_butaca (butaca_id, reserva_id, funcion_id, precio) VALUES
(1, 1, 1, 8.50),
(51, 2, 3, 7.50);

-- Insertar reserva producto
INSERT INTO reserva_producto (reserva_id, producto_id, precio_total) VALUES
(1, 1, 4.50),
(2, 2, 2.50);

-- Insertar opinión
INSERT INTO opinion (pelicula_id, usuario_id, comentario) VALUES
(1, 2, 'Excelente película'),
(3, 3, 'Muy buena actuación');



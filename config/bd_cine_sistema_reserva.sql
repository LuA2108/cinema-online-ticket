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
    numero INT UNIQUE NOT NULL
);

-- TIPO PRODUCTO ---------------------------
DROP TABLE if exists tipo;
CREATE TABLE if not exists tipo (
	id int auto_increment primary key,
	nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(100)
);

-- ESTADO RESERVA ---------------------------
DROP TABLE if exists estado;
CREATE TABLE if not exists estado (
	id int auto_increment primary key,
    nombre ENUM('pendiente', 'pagado', 'cancelado') DEFAULT 'pendiente'
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
    estado ENUM('activa','cancelada','finalizada'),
    
    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id) ON DELETE CASCADE,
    FOREIGN KEY (sala_id) REFERENCES sala(id) ON DELETE CASCADE
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
    foreign key (estado_id) references estado(id) ON DELETE CASCADE
);

-- RESERVA-BUTACA (Detalle de la reserva: Qué asientos son) ----------
DROP table if exists reserva_butaca;
CREATE TABLE if not exists reserva_butaca (
	butaca_id INT NOT NULL,
    reserva_id INT NOT NULL,
    funcion_id INT NOT NULL,
    precio DECIMAL(10,2),
    
    PRIMARY KEY (butaca_id, reserva_id, funcion_id),
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




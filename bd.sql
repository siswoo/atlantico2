DROP DATABASE IF EXISTS atlantico2;
CREATE DATABASE atlantico2;
USE atlantico2;

DROP TABLE IF EXISTS documento_tipo;
CREATE TABLE documento_tipo (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE documento_tipo CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO documento_tipo (nombre) VALUES 
('Documento de identidad'),
('Pasaporte'),
('PEP');

DROP TABLE IF EXISTS municipios;
CREATE TABLE municipios (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE municipios CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO municipios (nombre) VALUES 
('Palmar'),
('Baranoa'),
('Usiacuri'),
('Suan'),
('Santa Lucía'),
('Piojo');

DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	documento_tipo INT NOT NULL,
	documento_numero VARCHAR(250) NOT NULL,
	telefono VARCHAR(250) NOT NULL,
	correo VARCHAR(250) NOT NULL,
	usuario VARCHAR(250) NOT NULL,
	municipio INT NOT NULL,
	clave VARCHAR(250) NOT NULL,
	estatus INT NOT NULL,
	permisos INT NOT NULL,
	proyectos INT NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE usuarios CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO usuarios (nombre,documento_tipo,documento_numero,telefono,correo,usuario,clave,municipio,estatus,permisos,proyectos) VALUES 
('Juan Maldonado',3,'9559487081993','3016984868','juanmaldonado.co@gmail.com','juan1','e1f2e2d4f6598c43c2a45d2bd3acb7be',1,1,1,1),
('Jaime',1,'9999999999','999999999','jaime@gmail.com','jaime1','71b3b26aaa319e0cdf6fdb8429c112b0',1,1,1,1);


DROP TABLE IF EXISTS secciones;
CREATE TABLE secciones (
	id INT AUTO_INCREMENT,
	titulo VARCHAR(250) NOT NULL,
	tabla INT NOT NULL,
	proyecto INT NOT NULL,
	orden INT NOT NULL,
	estatus INT NOT NULL,
	fecha_creacion DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE secciones CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS proyectos;
CREATE TABLE proyectos (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	estatus INT NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE proyectos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS campos_tipos;
CREATE TABLE campos_tipos (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	fecha_creacion DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE campos_tipos CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO campos_tipos (nombre,fecha_creacion) VALUES 
('Input','2021-11-02'),
('Input Disabled','2021-11-02'),
('Select','2021-11-02'),
('Codigo','2021-11-02');

DROP TABLE IF EXISTS preguntas;
CREATE TABLE preguntas (
	id INT AUTO_INCREMENT,
	texto VARCHAR(250) NOT NULL,
	campos_tipos INT NOT NULL,
	secciones INT NOT NULL,
	ordenador VARCHAR(250) NOT NULL,
	tablet VARCHAR(250) NOT NULL,
	telefono VARCHAR(250) NOT NULL,
	orden INT NOT NULL,
	estatus INT NOT NULL,
	fecha_creacion DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE preguntas CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS preguntas_opciones;
CREATE TABLE preguntas_opciones (
	id INT AUTO_INCREMENT,
	texto VARCHAR(250) NOT NULL,
	preguntas INT NOT NULL,
	tabla VARCHAR(250) NOT NULL,
	condicional1 VARCHAR(250) NOT NULL,
	condicional2 VARCHAR(250) NOT NULL,
	condicional3 VARCHAR(250) NOT NULL,
	orden INT NOT NULL,
	fecha_creacion DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE preguntas_opciones CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS encuestas;
CREATE TABLE encuestas (
	id INT AUTO_INCREMENT,
	proyecto INT NOT NULL,
	seccion INT NOT NULL,
	pregunta INT NOT NULL,
	respuesta VARCHAR(250) NOT NULL,
	firma INT NOT NULL,
	responsable INT NOT NULL,
	fecha_creacion DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE encuestas CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS firmas;
CREATE TABLE firmas (
	id INT AUTO_INCREMENT,
	responsable INT NOT NULL,
	fecha_creacion DATE NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE firmas CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
-- Active: 1753196362703@@127.0.0.1@3306@scb
-- DROP DATABASE IF EXISTS scb;
CREATE DATABASE scb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE scb;

CREATE TABLE roles (
    idrol INT AUTO_INCREMENT PRIMARY KEY,
    rol VARCHAR(100) NOT NULL UNIQUE
);
-- Insertar roles por defecto
INSERT INTO roles (rol) VALUES ('Administrador'), ('Docente');

CREATE TABLE usuarios (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    email TEXT NOT NULL UNIQUE,
    passw VARCHAR(255) NOT NULL,
    idrol INT,
    estado TINYINT DEFAULT 1
);

INSERT INTO
    usuarios (usuario, email, passw, idrol)
VALUES (
        'admin',
        'admin@gmail.com',
        '$2y$10$oid89Rr2kf.Qm7yEaxIuneOJcYtTqDpP4JRmy6XLTQpQXUacF5grO',
        1
    );

ALTER TABLE usuarios
ADD FOREIGN KEY (idrol) REFERENCES roles (idrol) ON DELETE SET NULL ON UPDATE CASCADE;

CREATE TABLE prestamos (
    idprestamo INT PRIMARY KEY AUTO_INCREMENT,
    idusuario INT NOT NULL,
    idlibro INT NOT NULL,
    fechaprestamo date,
    fecharetorno date,
    estado INT NOT NULL
);

CREATE TABLE docentes (
    iddocente INT PRIMARY KEY AUTO_INCREMENT,
    nombres VARCHAR(100),
    apellidos VARCHAR(100),
    email TEXT,
    dui VARCHAR(15),
    estado INT
);

CREATE TABLE generos(
    idgenero INT PRIMARY KEY AUTO_INCREMENT,
    genero TEXT,
    descripcion TEXT
);

CREATE TABLE libros (
    idlibro INT PRIMARY KEY AUTO_INCREMENT,
    isbn TEXT NOT NULL,
    titulo TEXT NOT NULL,
    autor TEXT NOT NULL,
    idgenero INT,
    ejemplares INT,
    estado iNT
);

INSERT INTO generos (genero, descripcion) VALUES
('Novela', 'Obras de ficción extensas con desarrollo profundo de personajes y tramas.'),
('Cuento', 'Relatos breves con una única línea argumental y desenlace conciso.'),
('Ciencia ficción', 'Narraciones especulativas sobre avances científicos o tecnológicos y sus consecuencias.'),
('Fantasía', 'Historias con elementos mágicos, criaturas o mundos imaginarios.'),
('Misterio', 'Tramas centradas en resolver enigmas, desapariciones o secretos.'),
('Policíaco / Noir', 'Investigaciones de crímenes con detectives; tono oscuro y realista.'),
('Suspenso (Thriller)', 'Ritmo alto y tensión constante; persecuciones, conspiraciones y peligro.'),
('Romance', 'Relaciones amorosas como eje principal del conflicto y la trama.'),
('Terror', 'Busca provocar miedo o inquietud a través de lo sobrenatural o psicológico.'),
('Histórico', 'Ambientadas en épocas pasadas, con contexto y datos históricos verosímiles.'),
('Biografía', 'Relato de la vida de una persona, escrito por un tercero.'),
('Autobiografía', 'Relato de la vida de una persona, escrito por ella misma.'),
('Ensayo', 'Textos argumentativos y reflexivos sobre temas variados.'),
('Poesía', 'Composición en verso o prosa poética con lenguaje lírico y recursos estilísticos.'),
('Infantil', 'Historias dirigidas a niños, con lenguaje sencillo y temáticas formativas.'),
('Juvenil', 'Obras pensadas para adolescentes; crecimiento personal y conflictos propios de la edad.'),
('Aventura', 'Viajes, exploraciones y desafíos físicos o morales como motor narrativo.'),
('Distopía', 'Sociedades futuras u alternativas opresivas que cuestionan el orden social.'),
('Humor / Sátira', 'Crítica social o entretenimiento mediante ironía, parodia y comicidad.'),
('Divulgación científica', 'Libros que explican la ciencia al público general de forma clara y accesible.');

INSERT INTO libros (isbn, titulo, autor, idgenero, ejemplares, estado) VALUES
('9780000000001', 'Sombras en la Ciudad', 'Lucía Mendoza',
 (SELECT idgenero FROM generos WHERE genero='Novela'), 7, 1),

('9780000000002', 'Cuentos de la Medianoche', 'Mario R. Salvatierra',
 (SELECT idgenero FROM generos WHERE genero='Cuento'), 5, 1),

('9780000000003', 'Horizonte de Proción B', 'D. A. Robles',
 (SELECT idgenero FROM generos WHERE genero='Ciencia ficción'), 6, 1),

('9780000000004', 'El Reino de los Ocho Vientos', 'Ariadna Torres',
 (SELECT idgenero FROM generos WHERE genero='Fantasía'), 8, 1),

('9780000000005', 'La Llave del Invernadero', 'Esteban Núñez',
 (SELECT idgenero FROM generos WHERE genero='Misterio'), 4, 1),

('9780000000006', 'La Calle Sin Nombres', 'V. C. Herrera',
 (SELECT idgenero FROM generos WHERE genero='Policíaco / Noir'), 5, 1),

('9780000000007', 'Contrarreloj', 'Paula Montenegro',
 (SELECT idgenero FROM generos WHERE genero='Suspenso (Thriller)'), 6, 1),

('9780000000008', 'Cartas para Abril', 'Renata Campos',
 (SELECT idgenero FROM generos WHERE genero='Romance'), 9, 1),

('9780000000009', 'La Casa de las Puertas Rojas', 'J. E. Zamora',
 (SELECT idgenero FROM generos WHERE genero='Terror'), 3, 1),

('9780000000010', 'Crónicas del Virreinato', 'Héctor M. Cañas',
 (SELECT idgenero FROM generos WHERE genero='Histórico'), 4, 1),

('9780000000011', 'Ada Lovelace: La primera programadora', 'María del Sol Rivas',
 (SELECT idgenero FROM generos WHERE genero='Biografía'), 3, 1),

('9780000000012', 'Mi camino entre algoritmos', 'Rogelio Pérez',
 (SELECT idgenero FROM generos WHERE genero='Autobiografía'), 2, 1),

('9780000000013', 'Tecnología y Sociedad: Un diálogo', 'Camila P. Lobo',
 (SELECT idgenero FROM generos WHERE genero='Ensayo'), 5, 1),

('9780000000014', 'Versos del Trópico', 'Ignacio Beltrán',
 (SELECT idgenero FROM generos WHERE genero='Poesía'), 4, 1),

('9780000000015', 'El Robotito que Aprendió a Leer', 'Sofía Quintanilla',
 (SELECT idgenero FROM generos WHERE genero='Infantil'), 10, 1),

('9780000000016', 'Nivel 20: Evasión', 'Karla Domenech',
 (SELECT idgenero FROM generos WHERE genero='Juvenil'), 7, 1),

('9780000000017', 'Tras la Ruta del Jaguar', 'Tomás Villeda',
 (SELECT idgenero FROM generos WHERE genero='Aventura'), 6, 1),

('9780000000018', 'Ciudad 451', 'Nicolás Ferrer',
 (SELECT idgenero FROM generos WHERE genero='Distopía'), 5, 1),

('9780000000019', 'Manual del Burócrata Feliz', 'Elías Portillo',
 (SELECT idgenero FROM generos WHERE genero='Humor / Sátira'), 3, 1),

('9780000000020', 'El Cosmos en el Aula', 'Beatriz R. Cañadas',
 (SELECT idgenero FROM generos WHERE genero='Divulgación científica'), 6, 1);

-- 10 géneros de tecnología
INSERT INTO generos (genero, descripcion) VALUES
('Software', 'Libros sobre ingeniería de software, patrones, arquitectura y ciclo de vida.'),
('Hardware', 'Arquitectura de computadores, componentes, electrónica básica aplicada.'),
('Programación', 'Lenguajes, paradigmas, buenas prácticas y estructuras de datos.'),
('Redes y Telecomunicaciones', 'Protocolos, topologías, routing, switching y servicios de red.'),
('Desarrollo Web (HTML/CSS)', 'Maquetación, accesibilidad, responsive design y estándares web.'),
('Bases de Datos', 'Modelado, SQL, administración y optimización de consultas.'),
('Sistemas Operativos', 'Conceptos, administración y uso de sistemas Windows, Linux y otros.'),
('Seguridad Informática', 'Fundamentos, hardening, criptografía y pruebas de penetración.'),
('Inteligencia Artificial y Machine Learning', 'IA clásica, ML, redes neuronales y aplicaciones.'),
('DevOps y Computación en la Nube', 'CI/CD, contenedores, orquestación y servicios cloud.'); -- 10 libros de tecnología enlazados a los géneros anteriores

INSERT INTO libros (isbn, titulo, autor, idgenero, ejemplares, estado) VALUES
('9788400000101', 'Patrones de Diseño en Software Moderno', 'Laura Benítez',
 (SELECT idgenero FROM generos WHERE genero='Software' ORDER BY idgenero DESC LIMIT 1), 6, 1),

('9788400000102', 'Arquitectura de Computadores: De Bits a Sistemas', 'Héctor Iglesias',
 (SELECT idgenero FROM generos WHERE genero='Hardware' ORDER BY idgenero DESC LIMIT 1), 4, 1),

('9788400000103', 'Programación en Python: De Cero a Pro', 'Marina Cifuentes',
 (SELECT idgenero FROM generos WHERE genero='Programación' ORDER BY idgenero DESC LIMIT 1), 8, 1),

('9788400000104', 'Redes de Computadoras: Guía Práctica', 'Óscar Villalta',
 (SELECT idgenero FROM generos WHERE genero='Redes y Telecomunicaciones' ORDER BY idgenero DESC LIMIT 1), 5, 1),

('9788400000105', 'HTML & CSS: Diseño y Maquetación Web', 'Carolina Paredes',
 (SELECT idgenero FROM generos WHERE genero='Desarrollo Web (HTML/CSS)' ORDER BY idgenero DESC LIMIT 1), 10, 1),

('9788400000106', 'SQL y Modelado de Datos para Desarrolladores', 'Sergio Montalvo',
 (SELECT idgenero FROM generos WHERE genero='Bases de Datos' ORDER BY idgenero DESC LIMIT 1), 7, 1),

('9788400000107', 'Linux para Todos: Administración y Shell', 'Iván Rosales',
 (SELECT idgenero FROM generos WHERE genero='Sistemas Operativos' ORDER BY idgenero DESC LIMIT 1), 5, 1),

('9788400000108', 'Ciberseguridad Esencial: De 0 a Blue Team', 'Natalia Ríos',
 (SELECT idgenero FROM generos WHERE genero='Seguridad Informática' ORDER BY idgenero DESC LIMIT 1), 6, 1),

('9788400000109', 'Introducción a la Inteligencia Artificial', 'Julián Carranza',
 (SELECT idgenero FROM generos WHERE genero='Inteligencia Artificial y Machine Learning' ORDER BY idgenero DESC LIMIT 1), 4, 1),

('9788400000110', 'DevOps con Docker y Kubernetes', 'Beatriz Zamorano',
 (SELECT idgenero FROM generos WHERE genero='DevOps y Computación en la Nube' ORDER BY idgenero DESC LIMIT 1), 6, 1);

ALTER TABLE prestamos ADD FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario);
ALTER TABLE prestamos ADD FOREIGN KEY (idlibro) REFERENCES libros(idlibro);

ALTER TABLE docentes ADD FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario);


-- Base de datos para Revista Arcana (Sin elementos monetarios)
CREATE DATABASE IF NOT EXISTS revista_arcana CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE revista_arcana;

-- Tabla usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100),
    bio TEXT,
    avatar VARCHAR(255) DEFAULT 'A',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(10) DEFAULT '⚔',
    slug VARCHAR(50) UNIQUE NOT NULL
);

-- Tabla articulos
CREATE TABLE IF NOT EXISTS articulos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    contenido TEXT NOT NULL,
    autor_id INT NOT NULL,
    categoria_id INT NOT NULL,
    fecha DATE NOT NULL,
    tiempo_lectura INT DEFAULT 5,
    destacado TINYINT(1) DEFAULT 0,
    visitas INT DEFAULT 0,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

-- Tabla suscripciones_usuarios (gratuita)
CREATE TABLE IF NOT EXISTS suscripciones_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    autor_id INT NOT NULL,
    fecha_suscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE KEY unique_sub (usuario_id, autor_id)
);

-- Tabla suscripciones_categorias (gratuita)
CREATE TABLE IF NOT EXISTS suscripciones_categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    categoria_id INT NOT NULL,
    fecha_suscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cat_sub (usuario_id, categoria_id)
);

-- Insertar categorías
INSERT INTO categorias (nombre, descripcion, icono, slug) VALUES
('Sobre la fantasía', 'Ensayos y reflexiones sobre el género', '⚔', 'fantasia'),
('Mitología', 'Mitos, leyendas y su influencia creativa', '🌙', 'mitologia'),
('Creación de mundos', 'Técnicas y teoría del worldbuilding', '🌍', 'mundos'),
('Criaturas', 'Bestiarios y anatomía de lo fantástico', '🐉', 'criaturas'),
('Sistemas mágicos', 'Diseño y coherencia de la magia', '✨', 'magia'),
('Relatos originales', 'Ficción breve de la comunidad', '📜', 'relatos');

-- Insertar usuarios (contraseña: 123456)
INSERT INTO usuarios (username, email, password, nombre, bio, avatar) VALUES
('svrael', 'serafina@arcana.es', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Serafina Vrael', 'Editora fundadora de Arcana. Especialista en literatura fantástica y worldbuilding.', 'S'),
('mthalindor', 'mireya@arcana.es', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mireya Thalindor', 'Colaboradora especializada en criaturas y mitología.', 'M'),
('dkessvel', 'dorian@arcana.es', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dorian Kessvel', 'Colaborador experto en sistemas mágicos.', 'D'),
('isunveth', 'ilara@arcana.es', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ilaria Sunveth', 'Colaboradora especializada en cartografía y mundos.', 'I'),
('efarryn', 'elara@arcana.es', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Elara Farryn', 'Escritora de relatos originales.', 'E');

-- Insertar artículos
INSERT INTO articulos (titulo, contenido, autor_id, categoria_id, fecha, tiempo_lectura, destacado, visitas) VALUES
('El arte del Worldbuilding: cómo construir un mundo que respire', '<p>Crear un mundo fantástico va mucho más allá de dibujar un mapa o inventar un nombre. Cada detalle —la economía, el clima, las creencias populares— contribuye a la ilusión de que ese lugar existe más allá de las páginas.</p><h2>El primer error del worldbuilder</h2><p>La mayoría de creadores noveles empiezan por lo visible: el nombre del reino, el aspecto de la capital, el sistema de magia. Sin embargo, los mundos que perduran en la memoria del lector son aquellos que tienen raíces invisibles.</p><blockquote><p>"La historia visible es solo la punta del iceberg. Todo lo que el lector nunca verá es lo que mantiene el mundo a flote."</p><cite>— Ursula K. Le Guin</cite></blockquote><h2>Los cuatro pilares del worldbuilding coherente</h2><p>Existen cuatro dimensiones que todo mundo bien construido debe resolver, aunque el lector nunca las perciba directamente: geografía, historia, economía y creencias.</p>', 1, 3, '2025-04-14', 8, 1, 2400),

('Dragones: anatomía de una leyenda universal', '<p>Desde Fafnir hasta Smaug, los dragones comparten una presencia simbólica en culturas que nunca se conocieron. Analizamos el impacto de estas criaturas en la literatura fantástica.</p>', 2, 4, '2025-04-10', 6, 0, 1800),

('Las reglas de la magia: cuando lo imposible tiene lógica', '<p>Sanderson, Le Guin y Tolkien: tres filosofías del sistema mágico que cambiaron la fantasía moderna. La coherencia interna es clave.</p>', 3, 5, '2025-04-05', 9, 0, 2100),

('Cartografía fantástica: mapear lo que no existe', '<p>Un mapa bien diseñado no solo orienta al lector; define el ritmo de la aventura y revela la historia del mundo.</p>', 4, 3, '2025-04-02', 7, 0, 1600),

('Los héroes caídos: arquetipos que perduran', '<p>Aquiles, Sigfrido, Beowulf. Qué tienen en común estos héroes y por qué seguimos recreándolos hoy.</p>', 1, 2, '2025-03-28', 11, 0, 1950),

('¿Qué hace grande a un libro de fantasía?', '<p>Más allá de la magia y los dragones, los mejores libros del género comparten algo que va mucho más allá de la trama.</p>', 1, 1, '2025-03-21', 5, 0, 2200),

('La última lluvia de Iselth', '<p>Cuando el dios del agua cerró los ojos por última vez, Iselth comprendió que tendría que aprender a llorar por sí sola.</p>', 5, 6, '2025-03-15', 4, 0, 1300);

-- Suscripciones de ejemplo (usuario 1 sigue a varios autores y categorías)
INSERT INTO suscripciones_usuarios (usuario_id, autor_id) VALUES
(1, 2), (1, 3), (1, 4);

INSERT INTO suscripciones_categorias (usuario_id, categoria_id) VALUES
(1, 3), (1, 5), (1, 6), (1, 2);

CREATE DATABASE IF NOT EXISTS ecommerce_mvc
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ecommerce_mvc;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS detalle_pedido;
DROP TABLE IF EXISTS pedidos;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS configuracion;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE configuracion (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) NOT NULL UNIQUE,
    valor VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) DEFAULT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) DEFAULT NULL,
    dni VARCHAR(20) DEFAULT NULL,
    rol ENUM('cliente', 'empleado', 'administrador') NOT NULL DEFAULT 'cliente',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_acceso DATETIME DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    INDEX idx_usuarios_rol (rol),
    INDEX idx_usuarios_activo (activo)
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED DEFAULT NULL,
    nombre VARCHAR(100) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    descripcion VARCHAR(255) DEFAULT NULL,
    imagen VARCHAR(255) DEFAULT NULL,
    orden SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_categorias_parent
        FOREIGN KEY (parent_id) REFERENCES categorias(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    INDEX idx_categorias_parent (parent_id),
    INDEX idx_categorias_activo (activo)
) ENGINE=InnoDB;

CREATE TABLE productos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id BIGINT UNSIGNED NOT NULL,
    codigo VARCHAR(50) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    precio_oferta DECIMAL(10,2) DEFAULT NULL,
    stock INT UNSIGNED NOT NULL DEFAULT 0,
    imagen VARCHAR(255) DEFAULT NULL,
    destacado TINYINT(1) NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_productos_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    INDEX idx_productos_categoria (categoria_id),
    INDEX idx_productos_activo (activo),
    INDEX idx_productos_destacado (destacado)
) ENGINE=InnoDB;

CREATE TABLE pedidos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL UNIQUE,
    usuario_id BIGINT UNSIGNED DEFAULT NULL,
    cliente_nombre VARCHAR(150) NOT NULL,
    cliente_email VARCHAR(150) NOT NULL,
    cliente_telefono VARCHAR(20) DEFAULT NULL,
    direccion_envio VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    provincia VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(15) NOT NULL,
    pais VARCHAR(80) NOT NULL DEFAULT 'Espana',
    observaciones TEXT DEFAULT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    gastos_envio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'enviado', 'entregado') NOT NULL DEFAULT 'pendiente',
    metodo_pago VARCHAR(50) NOT NULL DEFAULT 'simulado',
    es_invitado TINYINT(1) NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedidos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,
    INDEX idx_pedidos_usuario (usuario_id),
    INDEX idx_pedidos_estado (estado),
    INDEX idx_pedidos_fecha (fecha_pedido)
) ENGINE=InnoDB;

CREATE TABLE detalle_pedido (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    producto_id BIGINT UNSIGNED NOT NULL,
    codigo_producto VARCHAR(50) NOT NULL,
    nombre_producto VARCHAR(150) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    cantidad INT UNSIGNED NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_detalle_pedido_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_pedido_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,
    INDEX idx_detalle_pedido_pedido (pedido_id),
    INDEX idx_detalle_pedido_producto (producto_id)
) ENGINE=InnoDB;

INSERT INTO configuracion (clave, valor) VALUES
('tienda_nombre', 'NovaShop'),
('tienda_email', 'noreply@novashop.local'),
('moneda', 'EUR'),
('gastos_envio_base', '4.99'),
('productos_por_pagina', '9');

INSERT INTO usuarios (id, nombre, apellidos, email, password, telefono, dni, rol, activo, ultimo_acceso) VALUES
(1, 'Ana', 'Admin Lopez', 'admin@novashop.local', '$2y$10$0GSgeS3VmQPCb9ZK6f2zsO9nDKlcWoWJp0VkFqsT.zRqtqo7Pl6qe', '600111111', '11111111A', 'administrador', 1, NOW()),
(2, 'Diego', 'Empleado Ruiz', 'empleado@novashop.local', '$2y$10$NeFy9ZDXEnXVoMrmYXpoNeGTiW6MdcBIXvV.5xi6Z0NlIHhcuawY6', '600222222', '22222222B', 'empleado', 1, NOW()),
(3, 'Lucia', 'Cliente Perez', 'cliente@novashop.local', '$2y$10$OuyNAXYajw4WwNB0kX3.a.S82FvQIMewaAWAHwb6qNST5m.xFjzxu', '600333333', '33333333C', 'cliente', 1, NOW()),
(4, 'Mario', 'Cliente Baja', 'cliente.inactivo@novashop.local', '$2y$10$OuyNAXYajw4WwNB0kX3.a.S82FvQIMewaAWAHwb6qNST5m.xFjzxu', '600444444', '44444444D', 'cliente', 0, NULL);

INSERT INTO categorias (id, parent_id, nombre, slug, descripcion, imagen, orden, activo) VALUES
(1, NULL, 'Gaming', 'gaming', 'Figuras, accesorios y articulos inspirados en videojuegos y sagas del mundo gaming.', 'PegasoAladoDeluxe.jpg', 1, 1),
(2, NULL, 'Comics y superheroes', 'comics-y-superheroes', 'Productos inspirados en heroes, villanos y sagas clasicas.', 'batman.jpg', 2, 1),
(3, NULL, 'Anime y manga', 'anime-y-manga', 'Coleccion anime con personajes y accesorios.', 'naruto.jpg', 3, 1),
(4, NULL, 'Marvel', 'marvel', 'Productos del universo Marvel.', 'spiderman.jpg', 4, 1),
(5, NULL, 'DC Comics', 'dc-comics', 'Productos del universo DC.', 'batman.jpg', 5, 1),
(6, NULL, 'Shonen', 'shonen', 'Series anime de accion y aventuras.', 'songoku.jpg', 6, 1),
(7, NULL, 'Mitologia y fantasia', 'mitologia-y-fantasia', 'Criaturas, heroes y mundos fantasticos.', 'ariadna.jpg', 7, 1);

INSERT INTO productos (id, categoria_id, codigo, nombre, slug, descripcion, precio, precio_oferta, stock, imagen, destacado, activo) VALUES
(1, 4, 'MAR-001', 'Figura Spiderman Premium', 'figura-spiderman-premium', 'Figura coleccionable de Spiderman con base decorativa y acabados premium.', 39.90, 34.90, 12, 'spiderman.jpg', 1, 1),
(2, 5, 'DC-001', 'Figura Batman Gotham', 'figura-batman-gotham', 'Figura de Batman inspirada en Gotham con detalles de capa y armadura.', 44.90, NULL, 8, 'batman.jpg', 1, 1),
(3, 6, 'ANI-001', 'Figura Naruto Hokage', 'figura-naruto-hokage', 'Edicion de Naruto con pose de combate y base expositora.', 29.95, 24.95, 18, 'naruto.jpg', 1, 1),
(4, 6, 'ANI-002', 'Figura Son Goku Saiyan', 'figura-son-goku-saiyan', 'Figura de Son Goku para fans de Dragon Ball con gran detalle.', 32.50, NULL, 14, 'songoku.jpg', 0, 1),
(5, 7, 'FAN-001', 'Ariadna Mitologica', 'ariadna-mitologica', 'Figura inspirada en la mitologia clasica, ideal para coleccionistas.', 27.90, NULL, 6, 'ariadna.jpg', 0, 1),
(6, 7, 'FAN-002', 'Pegaso Alado Deluxe', 'pegaso-alado-deluxe', 'Modelo de Pegaso con alas desplegadas y base efecto nube.', 49.95, 42.95, 4, 'PegasoAladoDeluxe.jpg', 1, 1),
(7, 4, 'MAR-002', 'Figura Venom Dark', 'figura-venom-dark', 'Figura oscura de Venom con acabado texturizado.', 37.80, NULL, 10, 'venom.jpg', 0, 1),
(8, 7, 'COL-001', 'Set Dragones Elementales', 'set-dragones-elementales', 'Pack de dragones coleccionables con acabados de fuego, agua y hielo.', 59.90, 54.90, 5, 'SetDragonesElementales.jpg', 1, 1),
(9, 7, 'COL-002', 'Kraken Atacante', 'kraken-atacante', 'Escultura fantastica de kraken para escaparate principal.', 46.75, NULL, 7, 'KrakenAtacante.jpg', 0, 1),
(10, 5, 'DC-002', 'Capitan America Retro', 'capitan-america-retro', 'Figura homenaje con escudo metalizado y caja de coleccion.', 35.00, NULL, 9, 'capitanamerica.jpg', 0, 0),
(11, 3, 'ANI-003', 'Figura Sailor Moon Eternal', 'figura-sailor-moon-eternal', 'Figura de Sailor Moon con pose iconica, acabados brillantes y base lunar para exposicion.', 31.95, 27.95, 11, 'naruto.jpg', 1, 1),
(12, 3, 'ANI-004', 'Figura Tanjiro Kamado', 'figura-tanjiro-kamado', 'Figura de Tanjiro inspirada en Demon Slayer con espada, capa y soporte de exposicion.', 34.50, NULL, 15, 'songoku.jpg', 0, 1),
(13, 3, 'ANI-005', 'Figura Levi Ackerman Elite', 'figura-levi-ackerman-elite', 'Edicion coleccionista de Levi con uniforme de maniobras tridimensionales y base urbana.', 41.90, 36.90, 7, 'naruto.jpg', 1, 1),
(14, 3, 'ANI-006', 'Figura Luffy Gear Fifth', 'figura-luffy-gear-fifth', 'Figura dinamica de Luffy con efecto nube y pose de combate para vitrina principal.', 38.75, NULL, 9, 'songoku.jpg', 1, 1),
(15, 1, 'GAM-001', 'Figura Link Hyrule Edition', 'figura-link-hyrule-edition', 'Figura inspirada en el heroe de Hyrule con espada, escudo y base expositora para fans del gaming.', 36.90, 32.90, 10, 'spiderman.jpg', 1, 1),
(16, 1, 'GAM-002', 'Figura Kratos Spartan Rage', 'figura-kratos-spartan-rage', 'Edicion de coleccion inspirada en un guerrero nordico con hacha detallada y acabados premium.', 44.50, NULL, 8, 'venom.jpg', 1, 1),
(17, 1, 'GAM-003', 'Figura Master Chief Armor', 'figura-master-chief-armor', 'Figura futurista con armadura verde y soporte para vitrina, pensada para coleccionistas gaming.', 39.95, 35.95, 12, 'batman.jpg', 0, 1),
(18, 1, 'GAM-004', 'Figura Mario Kart Racer', 'figura-mario-kart-racer', 'Figura inspirada en un icono del karting con pose dinamica y base de circuito para escaparate.', 29.95, NULL, 14, 'capitanamerica.jpg', 0, 1);

INSERT INTO pedidos (
    id, numero, usuario_id, cliente_nombre, cliente_email, cliente_telefono, direccion_envio, ciudad,
    provincia, codigo_postal, pais, observaciones, subtotal, gastos_envio, total, estado, metodo_pago,
    es_invitado, activo, fecha_pedido
) VALUES
(1, 'PED-20260423-0001', 3, 'Lucia Cliente Perez', 'cliente@novashop.local', '600333333', 'Calle Mayor 12', 'Madrid',
 'Madrid', '28001', 'Espana', 'Entrega por la manana.', 64.85, 4.99, 69.84, 'pendiente', 'simulado', 0, 1, '2026-04-23 10:00:00'),
(2, 'PED-20260423-0002', NULL, 'Invitado Demo', 'invitado@correo.test', '600555555', 'Avenida del Mar 45', 'Valencia',
 'Valencia', '46001', 'Espana', 'Llamar antes de entregar.', 42.95, 4.99, 47.94, 'enviado', 'simulado', 1, 1, '2026-04-23 12:30:00');

INSERT INTO detalle_pedido (pedido_id, producto_id, codigo_producto, nombre_producto, precio_unitario, cantidad, subtotal) VALUES
(1, 1, 'MAR-001', 'Figura Spiderman Premium', 34.90, 1, 34.90),
(1, 3, 'ANI-001', 'Figura Naruto Hokage', 24.95, 1, 24.95),
(1, 5, 'FAN-001', 'Ariadna Mitologica', 27.90, 1, 27.90),
(2, 6, 'FAN-002', 'Pegaso Alado Deluxe', 42.95, 1, 42.95);

CREATE DATABASE IF NOT EXISTS practica
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE practica;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = 'utf8mb4_unicode_ci';
-- =========================================================
-- TABLAS AUXILIARES
-- =========================================================

CREATE TABLE rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

-- =========================
-- PAIS
-- =========================
CREATE TABLE pais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    iso CHAR(2) NOT NULL,
    nombre VARCHAR(100) NOT NULL,

    UNIQUE (iso),
    UNIQUE (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =========================
-- PROVINCIAS (desde fichero)
-- =========================
CREATE TABLE provincia (
    id SMALLINT UNSIGNED NOT NULL,   -- idProvincia del fichero
    id_ccaa TINYINT UNSIGNED NOT NULL,
    nombre VARCHAR(30) NOT NULL,
    id_pais INT NOT NULL,            -- aÃ±adimos paÃ­s

    PRIMARY KEY (id),

    CONSTRAINT fk_provincia_pais
        FOREIGN KEY (id_pais)
        REFERENCES pais(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- =========================
-- LOCALIDADES (municipios)
-- =========================
CREATE TABLE localidad (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- puedes mantener auto_increment
    id_provincia SMALLINT UNSIGNED NOT NULL,
    cod_municipio INT NOT NULL,
    dc INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,

    CONSTRAINT fk_localidad_provincia
        FOREIGN KEY (id_provincia)
        REFERENCES provincia(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- ACTIVIDADES
-- =========================


CREATE TABLE tipo_actividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

-- =========================================================
-- USUARIOS
-- =========================================================

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(120) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    fecha_nacimiento DATE NOT NULL,

    id_tipo_actividad_preferida INT NULL,
    id_localidad INT NULL,
    id_provincia SMALLINT UNSIGNED NULL,
    id_pais INT NULL,
    id_rol INT NOT NULL,

    fecha_alta DATETIME NULL,
    codigo_validacion VARCHAR(100) NULL,
    fecha_baja DATETIME NULL,
    ultima_conexion DATETIME NULL,

    CONSTRAINT fk_usuario_tipo_actividad
        FOREIGN KEY (id_tipo_actividad_preferida) REFERENCES tipo_actividad(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_usuario_localidad
        FOREIGN KEY (id_localidad) REFERENCES localidad(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_usuario_provincia
        FOREIGN KEY (id_provincia) REFERENCES provincia(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_usuario_pais
        FOREIGN KEY (id_pais) REFERENCES pais(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_usuario_rol
        FOREIGN KEY (id_rol) REFERENCES rol(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =========================================================
-- IMÃGENES
-- =========================================================

CREATE TABLE imagen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    tamano BIGINT NOT NULL,
    alto INT NOT NULL,
    ancho INT NOT NULL,
    ruta VARCHAR(255) NOT NULL,
    es_perfil BOOLEAN NOT NULL DEFAULT FALSE,

    CONSTRAINT fk_imagen_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
-- ACTIVIDADES
-- =========================================================

CREATE TABLE actividad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    id_tipo_actividad INT NOT NULL,

    fecha_evento DATETIME NULL,
    fecha_publicacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    id_pais INT NULL,
    id_provincia SMALLINT UNSIGNED NULL,
    id_localidad INT NULL,

    archivo_gpx VARCHAR(255) NULL,

    CONSTRAINT fk_actividad_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_actividad_tipo
        FOREIGN KEY (id_tipo_actividad) REFERENCES tipo_actividad(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_actividad_pais
        FOREIGN KEY (id_pais) REFERENCES pais(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_actividad_provincia
        FOREIGN KEY (id_provincia) REFERENCES provincia(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE,

    CONSTRAINT fk_actividad_localidad
        FOREIGN KEY (id_localidad) REFERENCES localidad(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

-- =========================================================
-- AMIGOS / USUARIOS QUE SEGUIMOS
-- =========================================================

CREATE TABLE amistad (
    id_usuario INT NOT NULL,
    id_amigo INT NOT NULL,
    estado ENUM('pendiente', 'aceptada') NOT NULL DEFAULT 'pendiente',
    fecha_alta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_usuario, id_amigo),

    CONSTRAINT fk_amistad_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_amistad_amigo
        FOREIGN KEY (id_amigo) REFERENCES usuario(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);

-- =========================================================
-- COMPAÃ‘EROS DE ACTIVIDAD
-- =========================================================

CREATE TABLE actividad_companero (
    id_actividad INT NOT NULL,
    id_usuario INT NOT NULL,

    PRIMARY KEY (id_actividad, id_usuario),

    CONSTRAINT fk_actividad_companero_actividad
        FOREIGN KEY (id_actividad) REFERENCES actividad(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_actividad_companero_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
-- APLAUSOS
-- =========================================================

CREATE TABLE aplauso (
    id_actividad INT NOT NULL,
    id_usuario INT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_actividad, id_usuario),

    CONSTRAINT fk_aplauso_actividad
        FOREIGN KEY (id_actividad) REFERENCES actividad(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_aplauso_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
-- IMÃGENES ASOCIADAS A ACTIVIDADES
-- =========================================================

CREATE TABLE actividad_imagen (
    id_actividad INT NOT NULL,
    id_imagen INT NOT NULL,

    PRIMARY KEY (id_actividad, id_imagen),

    CONSTRAINT fk_actividad_imagen_actividad
        FOREIGN KEY (id_actividad) REFERENCES actividad(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_actividad_imagen_imagen
        FOREIGN KEY (id_imagen) REFERENCES imagen(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- =========================================================
-- DATOS INICIALES
-- =========================================================

INSERT INTO rol (nombre) VALUES
('administrador'),
('usuario');

INSERT INTO tipo_actividad (nombre) VALUES
('Ciclismo en ruta'),
('Ciclismo MTB'),
('Senderismo'),
('Carrera');



-- =========================================================
-- =========================================================
--                          VALUES
-- =========================================================
-- =========================================================



-- =========================================================
-- USUARIO ADMIN
-- =========================================================

INSERT INTO usuario (
    usuario,
    correo,
    contrasena,
    nombre,
    apellidos,
    fecha_nacimiento,
    id_tipo_actividad_preferida,
    id_localidad,
    id_provincia,
    id_pais,
    id_rol,
    fecha_alta
) VALUES (
    'admin',
    'admin@admin.com',
    '$2y$10$MnEudMCS7iY27u79grdda.XZ0Ptpeg20xxmElBTbvUnz.Z2bl4H9y', -- password: admin
    'Administrador',
    'Sistema',
    '2000-01-01',
    NULL,
    NULL,
    NULL,
    NULL,
    1,
    NOW()
);

-- =========================================================
-- PAIS
-- =========================================================

INSERT INTO pais (iso, nombre) VALUES
('AF', 'Afganistán'),
('AX', 'Islas Åland'),
('AL', 'Albania'),
('DE', 'Alemania'),
('AD', 'Andorra'),
('AO', 'Angola'),
('AI', 'Anguilla'),
('AQ', 'Antártida'),
('AG', 'Antigua y Barbuda'),
('AN', 'Antillas Holandesas'),
('SA', 'Arabia Saudí'),
('DZ', 'Argelia'),
('AR', 'Argentina'),
('AM', 'Armenia'),
('AW', 'Aruba'),
('AU', 'Australia'),
('AT', 'Austria'),
('AZ', 'Azerbaiyán'),
('BS', 'Bahamas'),
('BH', 'Baréin'),
('BD', 'Bangladesh'),
('BB', 'Barbados'),
('BY', 'Bielorrusia'),
('BE', 'Bélgica'),
('BZ', 'Belice'),
('BJ', 'Benín'),
('BM', 'Bermudas'),
('BT', 'Bután'),
('BO', 'Bolivia'),
('BA', 'Bosnia y Herzegovina'),
('BW', 'Botsuana'),
('BV', 'Isla Bouvet'),
('BR', 'Brasil'),
('BN', 'Brunéi'),
('BG', 'Bulgaria'),
('BF', 'Burkina Faso'),
('BI', 'Burundi'),
('CV', 'Cabo Verde'),
('KY', 'Islas Caimán'),
('KH', 'Camboya'),
('CM', 'Camerún'),
('CA', 'Canadá'),
('CF', 'República Centroafricana'),
('TD', 'Chad'),
('CZ', 'República Checa'),
('CL', 'Chile'),
('CN', 'China'),
('CY', 'Chipre'),
('CX', 'Isla de Navidad'),
('VA', 'Ciudad del Vaticano'),
('CC', 'Islas Cocos'),
('CO', 'Colombia'),
('KM', 'Comoras'),
('CD', 'República Democrática del Congo'),
('CG', 'Congo'),
('CK', 'Islas Cook'),
('KP', 'Corea del Norte'),
('KR', 'Corea del Sur'),
('CI', 'Costa de Marfil'),
('CR', 'Costa Rica'),
('HR', 'Croacia'),
('CU', 'Cuba'),
('DK', 'Dinamarca'),
('DM', 'Dominica'),
('DO', 'República Dominicana'),
('EC', 'Ecuador'),
('EG', 'Egipto'),
('SV', 'El Salvador'),
('AE', 'Emiratos Árabes Unidos'),
('ER', 'Eritrea'),
('SK', 'Eslovaquia'),
('SI', 'Eslovenia'),
('ES', 'España'),
('UM', 'Islas ultramarinas de Estados Unidos'),
('US', 'Estados Unidos'),
('EE', 'Estonia'),
('ET', 'Etiopía'),
('FO', 'Islas Feroe'),
('PH', 'Filipinas'),
('FI', 'Finlandia'),
('FJ', 'Fiyi'),
('FR', 'Francia'),
('GA', 'Gabón'),
('GM', 'Gambia'),
('GE', 'Georgia'),
('GS', 'Islas Georgias del Sur y Sandwich del Sur'),
('GH', 'Ghana'),
('GI', 'Gibraltar'),
('GD', 'Granada'),
('GR', 'Grecia'),
('GL', 'Groenlandia'),
('GP', 'Guadalupe'),
('GU', 'Guam'),
('GT', 'Guatemala'),
('GF', 'Guayana Francesa'),
('GN', 'Guinea'),
('GQ', 'Guinea Ecuatorial'),
('GW', 'Guinea-Bisáu'),
('GY', 'Guyana'),
('HT', 'Haití'),
('HM', 'Islas Heard y McDonald'),
('HN', 'Honduras'),
('HK', 'Hong Kong'),
('HU', 'Hungría'),
('IN', 'India'),
('ID', 'Indonesia'),
('IR', 'Irán'),
('IQ', 'Irak'),
('IE', 'Irlanda'),
('IS', 'Islandia'),
('IL', 'Israel'),
('IT', 'Italia'),
('JM', 'Jamaica'),
('JP', 'Japón'),
('JO', 'Jordania'),
('KZ', 'Kazajistán'),
('KE', 'Kenia'),
('KG', 'Kirguistán'),
('KI', 'Kiribati'),
('KW', 'Kuwait'),
('LA', 'Laos'),
('LS', 'Lesoto'),
('LV', 'Letonia'),
('LB', 'Líbano'),
('LR', 'Liberia'),
('LY', 'Libia'),
('LI', 'Liechtenstein'),
('LT', 'Lituania'),
('LU', 'Luxemburgo'),
('MO', 'Macao'),
('MK', 'Macedonia del Norte'),
('MG', 'Madagascar'),
('MY', 'Malasia'),
('MW', 'Malaui'),
('MV', 'Maldivas'),
('ML', 'Malí'),
('MT', 'Malta'),
('FK', 'Islas Malvinas'),
('MP', 'Islas Marianas del Norte'),
('MA', 'Marruecos'),
('MH', 'Islas Marshall'),
('MQ', 'Martinica'),
('MU', 'Mauricio'),
('MR', 'Mauritania'),
('YT', 'Mayotte'),
('MX', 'México'),
('FM', 'Micronesia'),
('MD', 'Moldavia'),
('MC', 'Mónaco'),
('MN', 'Mongolia'),
('MS', 'Montserrat'),
('MZ', 'Mozambique'),
('MM', 'Myanmar'),
('NA', 'Namibia'),
('NR', 'Nauru'),
('NP', 'Nepal'),
('NI', 'Nicaragua'),
('NE', 'Níger'),
('NG', 'Nigeria'),
('NU', 'Niue'),
('NF', 'Isla Norfolk'),
('NO', 'Noruega'),
('NC', 'Nueva Caledonia'),
('NZ', 'Nueva Zelanda'),
('OM', 'Omán'),
('NL', 'Países Bajos'),
('PK', 'Pakistán'),
('PW', 'Palaos'),
('PS', 'Palestina'),
('PA', 'Panamá'),
('PG', 'Papúa Nueva Guinea'),
('PY', 'Paraguay'),
('PE', 'Perú'),
('PN', 'Islas Pitcairn'),
('PF', 'Polinesia Francesa'),
('PL', 'Polonia'),
('PT', 'Portugal'),
('PR', 'Puerto Rico'),
('QA', 'Qatar'),
('GB', 'Reino Unido'),
('RE', 'Reunión'),
('RW', 'Ruanda'),
('RO', 'Rumanía'),
('RU', 'Rusia'),
('EH', 'Sahara Occidental'),
('SB', 'Islas Salomón'),
('WS', 'Samoa'),
('AS', 'Samoa Americana'),
('KN', 'San Cristóbal y Nieves'),
('SM', 'San Marino'),
('PM', 'San Pedro y Miquelón'),
('VC', 'San Vicente y las Granadinas'),
('SH', 'Santa Elena'),
('LC', 'Santa Lucía'),
('ST', 'Santo Tomé y Príncipe'),
('SN', 'Senegal'),
('RS', 'Serbia'),
('SC', 'Seychelles'),
('SL', 'Sierra Leona'),
('SG', 'Singapur'),
('SY', 'Siria'),
('SO', 'Somalia'),
('LK', 'Sri Lanka'),
('SZ', 'Esuatini'),
('ZA', 'Sudáfrica'),
('SD', 'Sudán'),
('SE', 'Suecia'),
('CH', 'Suiza'),
('SR', 'Surinam'),
('SJ', 'Svalbard y Jan Mayen'),
('TH', 'Tailandia'),
('TW', 'Taiwán'),
('TZ', 'Tanzania'),
('TJ', 'Tayikistán'),
('IO', 'Territorio Británico del Océano Índico'),
('TF', 'Territorios Australes Franceses'),
('TL', 'Timor Oriental'),
('TG', 'Togo'),
('TK', 'Tokelau'),
('TO', 'Tonga'),
('TT', 'Trinidad y Tobago'),
('TN', 'Túnez'),
('TC', 'Islas Turcas y Caicos'),
('TM', 'Turkmenistán'),
('TR', 'Turquía'),
('TV', 'Tuvalu'),
('UA', 'Ucrania'),
('UG', 'Uganda'),
('UY', 'Uruguay'),
('UZ', 'Uzbekistán'),
('VU', 'Vanuatu'),
('VE', 'Venezuela'),
('VN', 'Vietnam'),
('VG', 'Islas Vírgenes Británicas'),
('VI', 'Islas Vírgenes de los Estados Unidos'),
('WF', 'Wallis y Futuna'),
('YE', 'Yemen'),
('DJ', 'Yibuti'),
('ZM', 'Zambia'),
('ZW', 'Zimbabue');

-- =========================================================
-- PROVINCIAS
-- =========================================================

INSERT INTO provincia (id, id_ccaa, nombre, id_pais)
VALUES
(2, 8, 'Albacete', (SELECT id FROM pais WHERE iso = 'ES')),
(3, 10, 'Alicante/Alacant', (SELECT id FROM pais WHERE iso = 'ES')),
(4, 1, 'Almería', (SELECT id FROM pais WHERE iso = 'ES')),
(1, 16, 'Araba/Álava', (SELECT id FROM pais WHERE iso = 'ES')),
(33, 3, 'Asturias', (SELECT id FROM pais WHERE iso = 'ES')),
(5, 7, 'Ávila', (SELECT id FROM pais WHERE iso = 'ES')),
(6, 11, 'Badajoz', (SELECT id FROM pais WHERE iso = 'ES')),
(7, 4, 'Balears, Illes', (SELECT id FROM pais WHERE iso = 'ES')),
(8, 9, 'Barcelona', (SELECT id FROM pais WHERE iso = 'ES')),
(48, 16, 'Bizkaia', (SELECT id FROM pais WHERE iso = 'ES')),
(9, 7, 'Burgos', (SELECT id FROM pais WHERE iso = 'ES')),
(10, 11, 'Cáceres', (SELECT id FROM pais WHERE iso = 'ES')),
(11, 1, 'Cádiz', (SELECT id FROM pais WHERE iso = 'ES')),
(39, 6, 'Cantabria', (SELECT id FROM pais WHERE iso = 'ES')),
(12, 10, 'Castellón/Castelló', (SELECT id FROM pais WHERE iso = 'ES')),
(51, 18, 'Ceuta', (SELECT id FROM pais WHERE iso = 'ES')),
(13, 8, 'Ciudad Real', (SELECT id FROM pais WHERE iso = 'ES')),
(14, 1, 'Córdoba', (SELECT id FROM pais WHERE iso = 'ES')),
(15, 12, 'A Coruña', (SELECT id FROM pais WHERE iso = 'ES')),
(16, 8, 'Cuenca', (SELECT id FROM pais WHERE iso = 'ES')),
(20, 16, 'Gipuzkoa', (SELECT id FROM pais WHERE iso = 'ES')),
(17, 9, 'Girona', (SELECT id FROM pais WHERE iso = 'ES')),
(18, 1, 'Granada', (SELECT id FROM pais WHERE iso = 'ES')),
(19, 8, 'Guadalajara', (SELECT id FROM pais WHERE iso = 'ES')),
(21, 1, 'Huelva', (SELECT id FROM pais WHERE iso = 'ES')),
(22, 2, 'Huesca', (SELECT id FROM pais WHERE iso = 'ES')),
(23, 1, 'Jaén', (SELECT id FROM pais WHERE iso = 'ES')),
(24, 7, 'León', (SELECT id FROM pais WHERE iso = 'ES')),
(27, 12, 'Lugo', (SELECT id FROM pais WHERE iso = 'ES')),
(25, 9, 'Lleida', (SELECT id FROM pais WHERE iso = 'ES')),
(28, 13, 'Madrid', (SELECT id FROM pais WHERE iso = 'ES')),
(29, 1, 'Málaga', (SELECT id FROM pais WHERE iso = 'ES')),
(52, 19, 'Melilla', (SELECT id FROM pais WHERE iso = 'ES')),
(30, 14, 'Murcia', (SELECT id FROM pais WHERE iso = 'ES')),
(31, 15, 'Navarra', (SELECT id FROM pais WHERE iso = 'ES')),
(32, 12, 'Ourense', (SELECT id FROM pais WHERE iso = 'ES')),
(34, 7, 'Palencia', (SELECT id FROM pais WHERE iso = 'ES')),
(35, 5, 'Las Palmas', (SELECT id FROM pais WHERE iso = 'ES')),
(36, 12, 'Pontevedra', (SELECT id FROM pais WHERE iso = 'ES')),
(26, 17, 'La Rioja', (SELECT id FROM pais WHERE iso = 'ES')),
(37, 7, 'Salamanca', (SELECT id FROM pais WHERE iso = 'ES')),
(38, 5, 'Santa Cruz de Tenerife', (SELECT id FROM pais WHERE iso = 'ES')),
(40, 7, 'Segovia', (SELECT id FROM pais WHERE iso = 'ES')),
(41, 1, 'Sevilla', (SELECT id FROM pais WHERE iso = 'ES')),
(42, 7, 'Soria', (SELECT id FROM pais WHERE iso = 'ES')),
(43, 9, 'Tarragona', (SELECT id FROM pais WHERE iso = 'ES')),
(44, 2, 'Teruel', (SELECT id FROM pais WHERE iso = 'ES')),
(45, 8, 'Toledo', (SELECT id FROM pais WHERE iso = 'ES')),
(46, 10, 'Valencia/València', (SELECT id FROM pais WHERE iso = 'ES')),
(47, 7, 'Valladolid', (SELECT id FROM pais WHERE iso = 'ES')),
(49, 7, 'Zamora', (SELECT id FROM pais WHERE iso = 'ES')),
(50, 2, 'Zaragoza', (SELECT id FROM pais WHERE iso = 'ES'));
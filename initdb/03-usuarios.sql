SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = 'utf8mb4_unicode_ci';


INSERT INTO usuario (usuario, correo, contrasena, nombre, apellidos, fecha_nacimiento, id_tipo_actividad_preferida, id_localidad, id_provincia, id_pais, id_rol, fecha_alta) VALUES
('User01', 'user01@user.es', '$2y$10$I1RUb4NMQaxSNHRrCp9Of.oKwCcoKXLOxq5jdMaZmA/AwvT1/mFK.', 'User', '01', '2002-06-13', 4, 455, 23, 73, 2, NOW()),
('JuanPR', 'juanpr@user.es', '$2y$10$FoQymtjqv83.bSLJ9HpQ.O/o/nuwWgRcmlDZ2SSf33v83K7JBWdxi', 'Juan', 'Pérez Rodriguez', '1997-03-16', 1, 519, 38, 73, 2, NOW()),
('Diegui32', 'dieguito32@user.es', '$2y$10$.kJIBd6kzHjqa4/OtegpueGsASCOsemrfDKtfVsiGthkPG/GRzdty', 'Diego', 'Fernández López', '2001-06-13', 3, 637, 12, 73, 2, NOW()),
('MariGR', 'mariagr@user.es', '$2y$10$rT4ZKM6f6hU0u.L7HZEIBeEJrdg.Vn39i//kv0s0BcOQ5YtSk.HMq', 'María', 'García Ruiz', '1990-07-24', 1, 1827, 14, 73, 2, NOW()),
('Sergi_03', 'sergimolpri@user.es', '$2y$10$mYxSPXjV.XNZv.XXQdKUV.SH4QHbOC2BtI3jiYxP8/s42aSCFjQzm', 'Sergio', 'Molina Prieto', '2003-09-23', 2, 736, 16, 73, 2, NOW()),
('ElPablito08', 'pablomartin@user.es', '$2y$10$dvH56hGpUxwnLo/PY/KebuqGi9bi2S1Aac/yVUVGMgP/ICdunubFW', 'Pablo', 'Martín Herrera', '1990-03-08', 3, 2647, 23, 73, 2, NOW());
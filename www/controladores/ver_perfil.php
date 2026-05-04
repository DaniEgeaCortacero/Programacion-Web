<?php
require_once __DIR__ . "/db.php";

$id_usuario = $_SESSION["id_usuario"];

$sql = "SELECT 
            u.id,
            u.usuario,
            u.correo,
            u.nombre,
            u.apellidos,
            u.fecha_nacimiento,
            u.fecha_alta,
            ta.nombre AS tipo_actividad,
            p.nombre AS pais,
            pr.nombre AS provincia,
            l.nombre AS localidad,
            i.ruta AS imagen_perfil
        FROM usuario u
        LEFT JOIN tipo_actividad ta 
            ON u.id_tipo_actividad_preferida = ta.id
        LEFT JOIN pais p 
            ON u.id_pais = p.id
        LEFT JOIN provincia pr 
            ON u.id_provincia = pr.id
        LEFT JOIN localidad l 
            ON u.id_localidad = l.id
        LEFT JOIN imagen i 
            ON i.id_usuario = u.id AND i.es_perfil = 1
        WHERE u.id = ?
        AND u.fecha_baja IS NULL
        LIMIT 1";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

$resultado = $stmt->get_result();
$perfil = $resultado->fetch_assoc();

$stmt->close();
?>
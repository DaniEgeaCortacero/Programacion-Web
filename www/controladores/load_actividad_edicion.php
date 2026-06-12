<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

$evento_edicion = null;
$companeros_edicion = [];

if (!isset($_SESSION["id_usuario"])) {
    return;
}

$id_usuario = intval($_SESSION["id_usuario"]);
$id_actividad = intval($_GET["id"] ?? 0);

if ($id_actividad <= 0) {
    return;
}

$es_admin = isset($_SESSION["id_rol"]) && intval($_SESSION["id_rol"]) === 1;

if ($es_admin) {
    $sql = "SELECT 
                a.id,
                a.titulo,
                a.descripcion,
                a.id_tipo_actividad,
                a.fecha_evento,
                a.archivo_gpx,
                a.id_pais,
                a.id_provincia,
                a.id_localidad,
                p.nombre AS pais,
                pr.nombre AS provincia,
                l.nombre AS localidad
            FROM actividad a
            LEFT JOIN pais p ON p.id = a.id_pais
            LEFT JOIN provincia pr ON pr.id = a.id_provincia
            LEFT JOIN localidad l ON l.id = a.id_localidad
            WHERE a.id = ?
            LIMIT 1";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_actividad);

} else {
    $sql = "SELECT 
                a.id,
                a.titulo,
                a.descripcion,
                a.id_tipo_actividad,
                a.fecha_evento,
                a.archivo_gpx,
                a.id_pais,
                a.id_provincia,
                a.id_localidad,
                p.nombre AS pais,
                pr.nombre AS provincia,
                l.nombre AS localidad
            FROM actividad a
            LEFT JOIN pais p ON p.id = a.id_pais
            LEFT JOIN provincia pr ON pr.id = a.id_provincia
            LEFT JOIN localidad l ON l.id = a.id_localidad
            WHERE a.id = ?
            AND a.id_usuario = ?
            LIMIT 1";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $id_actividad, $id_usuario);
}


$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows === 0) {
    return;
}

$evento_edicion = $res->fetch_assoc();
$stmt->close();

$sql_comp = "SELECT id_usuario
             FROM actividad_companero
             WHERE id_actividad = ?";

$stmt = $mysqli->prepare($sql_comp);
$stmt->bind_param("i", $id_actividad);
$stmt->execute();

$res = $stmt->get_result();

while ($fila = $res->fetch_assoc()) {
    $companeros_edicion[] = intval($fila["id_usuario"]);
}

$stmt->close();
?>
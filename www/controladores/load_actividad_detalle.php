<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["id_usuario"])) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "No has iniciado sesión"
    ]);
    exit;
}

$id_usuario_actual = intval($_SESSION["id_usuario"]);
$id_actividad = intval($_GET["id"] ?? 0);

if ($id_actividad <= 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Actividad no válida"
    ]);
    exit;
}

/* ################ ACTIVIDAD ################ */

$sql = "SELECT 
            a.id,
            a.titulo,
            a.descripcion,
            a.archivo_gpx,
            a.fecha_evento,
            a.fecha_publicacion,

            ta.nombre AS tipo_actividad,

            u.id AS id_usuario_publicador,
            u.usuario AS usuario_publicador,
            u.nombre AS nombre_publicador,
            u.apellidos AS apellidos_publicador,

            ip.ruta AS imagen_publicador

        FROM actividad a

        JOIN usuario u 
            ON a.id_usuario = u.id

        JOIN tipo_actividad ta 
            ON a.id_tipo_actividad = ta.id

        LEFT JOIN imagen ip
            ON ip.id_usuario = u.id
            AND ip.es_perfil = 1

        WHERE a.id = ?
        LIMIT 1";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_actividad);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Actividad no encontrada"
    ]);
    exit;
}

$actividad = $resultado->fetch_assoc();
$stmt->close();

/* ################ IMÁGENES ################ */

$sql_img = "SELECT 
                i.id,
                i.ruta,
                i.nombre
            FROM actividad_imagen ai
            JOIN imagen i 
                ON ai.id_imagen = i.id
            WHERE ai.id_actividad = ?";

$stmt_img = $mysqli->prepare($sql_img);
$stmt_img->bind_param("i", $id_actividad);
$stmt_img->execute();

$res_img = $stmt_img->get_result();

$imagenes = [];

while ($img = $res_img->fetch_assoc()) {
    $imagenes[] = $img;
}

$stmt_img->close();

/* ################ COMPAÑEROS ################ */

$sql_comp = "SELECT
                u.id,
                u.usuario,
                u.nombre,
                u.apellidos,
                ip.ruta AS imagen_perfil
            FROM actividad_companero ac
            JOIN usuario u 
                ON u.id = ac.id_usuario
            LEFT JOIN imagen ip 
                ON ip.id_usuario = u.id
                AND ip.es_perfil = 1
            WHERE ac.id_actividad = ?";

$stmt_comp = $mysqli->prepare($sql_comp);
$stmt_comp->bind_param("i", $id_actividad);
$stmt_comp->execute();

$res_comp = $stmt_comp->get_result();

$companeros = [];

while ($comp = $res_comp->fetch_assoc()) {
    $companeros[] = $comp;
}

$stmt_comp->close();

/* ################ APLAUSOS ################ */

$sql_aplausos = "SELECT COUNT(*) AS total
                 FROM aplauso
                 WHERE id_actividad = ?";

$stmt_aplausos = $mysqli->prepare($sql_aplausos);
$stmt_aplausos->bind_param("i", $id_actividad);
$stmt_aplausos->execute();

$res_aplausos = $stmt_aplausos->get_result();
$fila_aplausos = $res_aplausos->fetch_assoc();

$n_aplausos = intval($fila_aplausos["total"]);

$stmt_aplausos->close();

/* ################ MI APLAUSO ################ */

$sql_mio = "SELECT 1
            FROM aplauso
            WHERE id_actividad = ?
            AND id_usuario = ?
            LIMIT 1";

$stmt_mio = $mysqli->prepare($sql_mio);
$stmt_mio->bind_param("ii", $id_actividad, $id_usuario_actual);
$stmt_mio->execute();

$res_mio = $stmt_mio->get_result();

$mi_aplauso = ($res_mio->num_rows > 0);

$stmt_mio->close();

/* ################ RESPUESTA JSON ################ */

echo json_encode([
    "ok" => true,
    "actividad" => $actividad,
    "imagenes" => $imagenes,
    "companeros" => $companeros,
    "n_aplausos" => $n_aplausos,
    "mi_aplauso" => $mi_aplauso
]);
?>
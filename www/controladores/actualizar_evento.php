<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    exit("No autenticado");
}

$id_usuario = intval($_SESSION["id_usuario"]);
$id_actividad = intval($_POST["id_actividad"] ?? 0);

$titulo = trim($_POST["titulo"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$id_tipo_actividad = intval($_POST["tipo"] ?? 0);

$id_pais = intval($_POST["pais"] ?? 0);
$id_provincia = intval($_POST["provincia"] ?? 0);
$id_localidad = intval($_POST["localidad"] ?? 0);

$fecha = $_POST["fecha"] ?? "";
$hora = $_POST["hora"] ?? "";

if ($id_actividad <= 0 || $titulo === "" || $id_tipo_actividad <= 0 || $fecha === "" || $hora === "") {
    exit("Datos incompletos");
}

$fecha_evento = $fecha . " " . $hora . ":00";

$es_admin = isset($_SESSION["id_rol"]) && intval($_SESSION["id_rol"]) === 1;

if ($es_admin) {

    $sql = "UPDATE actividad
        SET titulo = ?,
            descripcion = ?,
            id_tipo_actividad = ?,
            fecha_evento = ?,
            id_pais = ?,
            id_provincia = ?,
            id_localidad = ?
        WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(
        "ssisiiii",
        $titulo,
        $descripcion,
        $id_tipo_actividad,
        $fecha_evento,
        $id_pais,
        $id_provincia,
        $id_localidad,
        $id_actividad
    );

} else {

    $sql = "UPDATE actividad
        SET titulo = ?,
            descripcion = ?,
            id_tipo_actividad = ?,
            fecha_evento = ?,
            id_pais = ?,
            id_provincia = ?,
            id_localidad = ?
        WHERE id = ?
        AND id_usuario = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "ssisiiiii",
            $titulo,
            $descripcion,
            $id_tipo_actividad,
            $fecha_evento,
            $id_pais,
            $id_provincia,
            $id_localidad,
            $id_actividad,
            $id_usuario
        );
}

if (!$stmt->execute()) {
    exit("Error al actualizar actividad");
}

$stmt->close();

/*
    Actualizar compañeros
*/
$sql_delete = "DELETE FROM actividad_companero
               WHERE id_actividad = ?";

$stmt = $mysqli->prepare($sql_delete);
$stmt->bind_param("i", $id_actividad);
$stmt->execute();
$stmt->close();

$companeros = $_POST["companeros"] ?? [];

if (!is_array($companeros)) {
    $companeros = [];
}

$sql_comp = "INSERT IGNORE INTO actividad_companero 
             (id_actividad, id_usuario)
             SELECT ?, u.id
             FROM usuario u
             JOIN amistad a 
                ON a.id_amigo = u.id
                AND a.id_usuario = ?
                AND a.estado = 'aceptada'
             WHERE u.id = ?";

$stmt = $mysqli->prepare($sql_comp);

foreach ($companeros as $id_companero) {
    $id_companero = intval($id_companero);

    if ($id_companero <= 0 || $id_companero == $id_usuario) {
        continue;
    }

    $stmt->bind_param("iii", $id_actividad, $id_usuario, $id_companero);
    $stmt->execute();
}

$stmt->close();

header("Location: ../html/prototipo_main.php?vista=amistad_detalles&id=" . $id_usuario);
exit;
?>
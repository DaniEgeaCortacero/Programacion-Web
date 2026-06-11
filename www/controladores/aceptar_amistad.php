<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    exit("Debes iniciar sesión");
}

$id_usuario_actual = intval($_SESSION["id_usuario"]);
$id_solicitante = intval($_POST["id_usuario"] ?? 0);

if ($id_solicitante <= 0 || $id_solicitante === $id_usuario_actual) {
    exit("Solicitud no válida");
}

/*
    Comprobamos que realmente exista una solicitud pendiente:
    solicitante -> yo
*/
$sql_check = "SELECT estado
              FROM amistad
              WHERE id_usuario = ?
              AND id_amigo = ?
              LIMIT 1";

$stmt = $mysqli->prepare($sql_check);
$stmt->bind_param("ii", $id_solicitante, $id_usuario_actual);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows === 0) {
    exit("No existe solicitud pendiente");
}

$fila = $res->fetch_assoc();

if ($fila["estado"] !== "pendiente") {
    exit("Esta solicitud ya no está pendiente");
}

$stmt->close();

/*
    1. Actualizar la solicitud recibida a aceptada
*/
$sql_update = "UPDATE amistad
               SET estado = 'aceptada'
               WHERE id_usuario = ?
               AND id_amigo = ?";

$stmt = $mysqli->prepare($sql_update);
$stmt->bind_param("ii", $id_solicitante, $id_usuario_actual);

if (!$stmt->execute()) {
    exit("Error al aceptar solicitud");
}

$stmt->close();

/*
    2. Insertar la relación inversa aceptada
*/
$sql_insert = "INSERT INTO amistad (id_usuario, id_amigo, estado)
               VALUES (?, ?, 'aceptada')
               ON DUPLICATE KEY UPDATE estado = 'aceptada'";

$stmt = $mysqli->prepare($sql_insert);
$stmt->bind_param("ii", $id_usuario_actual, $id_solicitante);

if ($stmt->execute()) {
    echo "Solicitud aceptada";
} else {
    echo "Error al crear amistad";
}

$stmt->close();
?>
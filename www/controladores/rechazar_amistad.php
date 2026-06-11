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
    Borra solo la solicitud pendiente:
    solicitante -> yo
*/
$sql = "DELETE FROM amistad
        WHERE id_usuario = ?
        AND id_amigo = ?
        AND estado = 'pendiente'";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $id_solicitante, $id_usuario_actual);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo "Solicitud rechazada";
    } else {
        echo "No existe solicitud pendiente";
    }
} else {
    echo "Error al rechazar solicitud";
}

$stmt->close();
?>
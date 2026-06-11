<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    return;
}

$id_usuario = intval($_SESSION["id_usuario"]);

$sql = "UPDATE usuario
        SET ultima_conexion = NOW()
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->close();
?>
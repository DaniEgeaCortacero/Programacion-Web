<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    exit("No autenticado");
}

$id_usuario = intval($_SESSION["id_usuario"]);
$id_actividad = intval($_POST["id_actividad"] ?? 0);
$es_admin = isset($_SESSION["id_rol"]) && intval($_SESSION["id_rol"]) === 1;

if ($id_actividad <= 0) {
    exit("Actividad no válida");
}

if ($es_admin) {
    $sql = "DELETE FROM actividad WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_actividad);
} else {
    $sql = "DELETE FROM actividad
            WHERE id = ?
            AND id_usuario = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $id_actividad, $id_usuario);
}

if ($stmt->execute()) {
    echo $stmt->affected_rows > 0 
        ? "Actividad eliminada" 
        : "No tienes permiso para eliminar esta actividad";
} else {
    echo "Error al eliminar actividad";
}

$stmt->close();
?>
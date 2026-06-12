<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    exit("Debes iniciar sesión");
}

$id_usuario_actual = intval($_SESSION["id_usuario"]);
$id_amigo = intval($_POST["id_amigo"] ?? 0);

if ($id_amigo <= 0 || $id_amigo === $id_usuario_actual) {
    exit("Amistad no válida");
}

$sql = "DELETE FROM amistad
        WHERE estado = 'aceptada'
        AND (
            (id_usuario = ? AND id_amigo = ?)
            OR
            (id_usuario = ? AND id_amigo = ?)
        )";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    exit("Error al preparar consulta: " . $mysqli->error);
}

$stmt->bind_param(
    "iiii",
    $id_usuario_actual,
    $id_amigo,
    $id_amigo,
    $id_usuario_actual
);

if ($stmt->execute()) {
    echo $stmt->affected_rows > 0
        ? "Amistad eliminada"
        : "No existía una amistad aceptada";
} else {
    echo "Error al eliminar amistad";
}

$stmt->close();
?>
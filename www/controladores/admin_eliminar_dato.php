<?php
session_start();
require_once __DIR__ . "/admin_guard.php";
require_once __DIR__ . "/db.php";

$seccion = $_POST["seccion"] ?? "";
$id = intval($_POST["id"] ?? 0);

if ($id <= 0) {
    exit("ID no válido");
}

if ($seccion === "tipos") {
    $sql = "DELETE FROM tipo_actividad WHERE id = ?";
} elseif ($seccion === "paises") {
    $sql = "DELETE FROM pais WHERE id = ?";
} elseif ($seccion === "provincias") {
    $sql = "DELETE FROM provincia WHERE id = ?";
} elseif ($seccion === "localidades") {
    $sql = "DELETE FROM localidad WHERE id = ?";
} else {
    exit("Sección no válida");
}

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    echo "No se puede eliminar. Puede estar siendo usado por usuarios o actividades.";
    exit;
}

$stmt->close();

header("Location: ../html/prototipo_main.php?vista=admin_datos&seccion=" . urlencode($seccion));
exit;
?>
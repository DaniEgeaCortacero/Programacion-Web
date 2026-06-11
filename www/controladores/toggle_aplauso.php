<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["id_usuario"])) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Debes iniciar sesión"
    ]);
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);
$id_actividad = intval($_POST["id_actividad"] ?? 0);

if ($id_actividad <= 0) {
    echo json_encode([
        "ok" => false,
        "mensaje" => "Actividad no válida"
    ]);
    exit;
}

// Comprobar si ya existe aplauso
$sql_check = "SELECT 1
              FROM aplauso
              WHERE id_actividad = ?
              AND id_usuario = ?
              LIMIT 1";

$stmt = $mysqli->prepare($sql_check);
$stmt->bind_param("ii", $id_actividad, $id_usuario);
$stmt->execute();

$res = $stmt->get_result();
$ya_aplaudio = $res->num_rows > 0;

$stmt->close();

if ($ya_aplaudio) {
    // Quitar aplauso
    $sql = "DELETE FROM aplauso
            WHERE id_actividad = ?
            AND id_usuario = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $id_actividad, $id_usuario);
    $stmt->execute();
    $stmt->close();

    $activo = false;
} else {
    // Dar aplauso
    $sql = "INSERT INTO aplauso (id_actividad, id_usuario)
            VALUES (?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ii", $id_actividad, $id_usuario);
    $stmt->execute();
    $stmt->close();

    $activo = true;
}

// Contar aplausos actualizados
$sql_count = "SELECT COUNT(*) AS total
              FROM aplauso
              WHERE id_actividad = ?";

$stmt = $mysqli->prepare($sql_count);
$stmt->bind_param("i", $id_actividad);
$stmt->execute();

$res = $stmt->get_result();
$fila = $res->fetch_assoc();

$stmt->close();

echo json_encode([
    "ok" => true,
    "activo" => $activo,
    "total" => intval($fila["total"])
]);
?>
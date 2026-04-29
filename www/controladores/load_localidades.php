<?php
require_once __DIR__ . "/db.php";

$id_provincia = $_GET["id_provincia"] ?? 0;

$stmt = $mysqli->prepare("SELECT id, nombre FROM localidad WHERE id_provincia = ? ORDER BY nombre");
$stmt->bind_param("i", $id_provincia);
$stmt->execute();

$resultado = $stmt->get_result();
$localidades = [];

while ($row = $resultado->fetch_assoc()) {
    $localidades[] = $row;
}

header("Content-Type: application/json");
echo json_encode($localidades);
?>
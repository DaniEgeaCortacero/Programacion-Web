<?php
require_once __DIR__ . "/db.php";

$id_pais = $_GET["id_pais"] ?? 0;

$stmt = $mysqli->prepare("SELECT id, nombre FROM provincia WHERE id_pais = ? ORDER BY nombre");
$stmt->bind_param("i", $id_pais);
$stmt->execute();

$resultado = $stmt->get_result();
$provincias = [];

while ($row = $resultado->fetch_assoc()) {
    $provincias[] = $row;
}

header("Content-Type: application/json");
echo json_encode($provincias);
?>
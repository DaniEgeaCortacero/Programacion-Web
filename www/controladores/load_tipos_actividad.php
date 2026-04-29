<?php
require_once __DIR__ . "/db.php";

$resultado = $mysqli->query("SELECT id, nombre FROM tipo_actividad ORDER BY nombre");

$tipos_actividad = [];

while ($row = $resultado->fetch_assoc()) {
    $tipos_actividad[] = $row;
}
?>
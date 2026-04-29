<?php
require_once __DIR__ . "/db.php";

$resultado = $mysqli->query("SELECT id, iso, nombre FROM pais ORDER BY nombre");

$paises = [];
while ($row = $resultado->fetch_assoc()) {
    $paises[] = $row;
}
?>
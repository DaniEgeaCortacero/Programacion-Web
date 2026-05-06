<?php
require_once __DIR__ . "/db.php";

function obtenerImagenesActividad($mysqli, $id_actividad) {

    $sql = "SELECT 
                i.id,
                i.ruta,
                i.nombre
            FROM actividad_imagen ai
            JOIN imagen i ON ai.id_imagen = i.id
            WHERE ai.id_actividad = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_actividad);
    $stmt->execute();

    $resultado = $stmt->get_result();

    $imagenes = [];

    while ($fila = $resultado->fetch_assoc()) {
        $imagenes[] = $fila;
    }

    $stmt->close();

    return $imagenes;
}
?>
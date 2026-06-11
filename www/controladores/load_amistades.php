<?php
require_once __DIR__ . "/db.php";

$id_usuario = $_SESSION["id_usuario"];

$sql = "SELECT 
            u.id,
            u.usuario,
            u.ultima_conexion,
            i.ruta AS imagen,
            CASE 
                WHEN u.ultima_conexion >= NOW() - INTERVAL 10 MINUTE 
                THEN 1 
                ELSE 0 
            END AS conectado
        FROM amistad a
        JOIN usuario u ON a.id_amigo = u.id
        LEFT JOIN imagen i 
            ON i.id_usuario = u.id AND i.es_perfil = 1
        WHERE a.id_usuario = ?
        AND a.estado = 'aceptada'
        AND u.fecha_baja IS NULL";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

$resultado = $stmt->get_result();

$amistades = [];

while($row = $resultado->fetch_assoc()){
    $amistades[] = $row;
}

$stmt->close();

?>
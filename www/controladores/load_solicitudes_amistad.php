<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

$id_usuario_actual = $_SESSION["id_usuario"] ?? null;

$solicitudes = [];

if (!$id_usuario_actual) {
    return;
}

$sql = "SELECT 
            u.id,
            u.usuario,
            u.nombre,
            u.apellidos,
            i.ruta AS foto_perfil
        FROM amistad a
        JOIN usuario u 
            ON u.id = a.id_usuario
        LEFT JOIN imagen i 
            ON i.id_usuario = u.id
            AND i.es_perfil = 1
        WHERE a.id_amigo = ?
        AND a.estado = 'pendiente'
        AND u.fecha_baja IS NULL
        ORDER BY a.fecha_alta DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario_actual);
$stmt->execute();

$resultado = $stmt->get_result();

while ($fila = $resultado->fetch_assoc()) {
    $solicitudes[] = $fila;
}

$stmt->close();
?>
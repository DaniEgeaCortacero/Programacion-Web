<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    exit;
}

$id_usuario_actual = intval($_SESSION["id_usuario"]);
$busqueda = $_GET["busqueda"] ?? "";

if (strlen(trim($busqueda)) < 2) {
    exit;
}

$sql = "SELECT 
            u.id,
            u.usuario,
            u.nombre,
            u.apellidos,
            i.ruta AS foto_perfil
        FROM usuario u
        LEFT JOIN imagen i 
            ON i.id_usuario = u.id
            AND i.es_perfil = 1
        WHERE 
            u.id != ?
            AND u.fecha_baja IS NULL
            AND (
                u.usuario LIKE ?
                OR u.nombre LIKE ?
                OR u.apellidos LIKE ?
                OR CONCAT(u.nombre, ' ', u.apellidos) LIKE ?
            )
        LIMIT 10";

$stmt = $mysqli->prepare($sql);

$like = "%" . $busqueda . "%";

$stmt->bind_param(
    "issss",
    $id_usuario_actual,
    $like,
    $like,
    $like,
    $like
);

$stmt->execute();

$resultado = $stmt->get_result();

while ($u = $resultado->fetch_assoc()) {
    $modo_usuario = "busqueda";
    include __DIR__ . "/../html/vistas/usuario_encontrado.php";
}

$stmt->close();
?>
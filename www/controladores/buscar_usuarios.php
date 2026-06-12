<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    exit;
}

$id_usuario_actual = intval($_SESSION["id_usuario"]);
$busqueda = trim($_GET["busqueda"] ?? "");

if (strlen($busqueda) < 2) {
    exit;
}

$sql = "SELECT 
            u.id,
            u.usuario,
            u.nombre,
            u.apellidos,
            i.ruta AS foto_perfil,

            a1.estado AS estado_directo,
            a2.estado AS estado_inverso

        FROM usuario u

        LEFT JOIN imagen i 
            ON i.id_usuario = u.id
            AND i.es_perfil = 1

        LEFT JOIN amistad a1
            ON a1.id_usuario = ?
            AND a1.id_amigo = u.id

        LEFT JOIN amistad a2
            ON a2.id_usuario = u.id
            AND a2.id_amigo = ?

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
    "iiissss",
    $id_usuario_actual,
    $id_usuario_actual,
    $id_usuario_actual,
    $like,
    $like,
    $like,
    $like
);

$stmt->execute();
$resultado = $stmt->get_result();

while ($u = $resultado->fetch_assoc()) {

    if ($u["estado_directo"] === "aceptada" || $u["estado_inverso"] === "aceptada") {
        $modo_usuario = "amistad";

    } elseif ($u["estado_directo"] === "pendiente") {
        $modo_usuario = "solicitud_enviada";

    } elseif ($u["estado_inverso"] === "pendiente") {
        $modo_usuario = "solicitud";

    } else {
        $modo_usuario = "busqueda";
    }

    include __DIR__ . "/../html/vistas/usuario_encontrado.php";
}

$stmt->close();
?>
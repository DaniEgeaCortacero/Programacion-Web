<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    exit("Debes iniciar sesión");
}

$id_usuario_actual = intval($_SESSION["id_usuario"]);
$es_admin = isset($_SESSION["id_rol"]) && intval($_SESSION["id_rol"]) === 1;

$vista_actual = $_GET["vista"] ?? "";


$id_usuario = isset($_GET["id"]) ? intval($_GET["id"]) : $id_usuario_actual;

$perfil = null;
$es_mi_perfil = ($id_usuario === $id_usuario_actual);
$es_amigo = false;
$estado_relacion = "ninguna";

$permitir_baja = $es_admin && ($_GET["vista"] ?? "") === "admin_editar_usuario";

$filtro_baja = $permitir_baja
    ? ""
    : "AND u.fecha_baja IS NULL";

$sql = "SELECT 
            u.id,
            u.id_rol,
            u.usuario,
            u.correo,
            u.nombre,
            u.apellidos,
            u.fecha_nacimiento,
            u.fecha_alta,
            u.fecha_baja,
            u.id_tipo_actividad_preferida,
            ta.nombre AS tipo_actividad,
            p.nombre AS pais,
            pr.nombre AS provincia,
            l.nombre AS localidad,
            i.ruta AS imagen_perfil
        FROM usuario u
        LEFT JOIN tipo_actividad ta ON u.id_tipo_actividad_preferida = ta.id
        LEFT JOIN pais p ON u.id_pais = p.id
        LEFT JOIN provincia pr ON u.id_provincia = pr.id
        LEFT JOIN localidad l ON u.id_localidad = l.id
        LEFT JOIN imagen i ON i.id_usuario = u.id AND i.es_perfil = 1
        WHERE u.id = ?
        $filtro_baja
        LIMIT 1";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

$resultado = $stmt->get_result();
$perfil = $resultado->fetch_assoc();

$stmt->close();

if (!$perfil) {
    return;
}

/*
    Comprobar relación de amistad
*/
if ($es_mi_perfil) {
    $es_amigo = true;
    $estado_relacion = "propio";
} else {
    $sql = "SELECT id_usuario, id_amigo, estado
            FROM amistad
            WHERE 
                (id_usuario = ? AND id_amigo = ?)
                OR
                (id_usuario = ? AND id_amigo = ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(
        "iiii",
        $id_usuario_actual,
        $id_usuario,
        $id_usuario,
        $id_usuario_actual
    );
    $stmt->execute();

    $res = $stmt->get_result();

    while ($rel = $res->fetch_assoc()) {
        if ($rel["estado"] === "aceptada") {
            $es_amigo = true;
            $estado_relacion = "aceptada";
            break;
        }

        if (
            intval($rel["id_usuario"]) === $id_usuario_actual &&
            $rel["estado"] === "pendiente"
        ) {
            $estado_relacion = "pendiente_enviada";
        }

        if (
            intval($rel["id_amigo"]) === $id_usuario_actual &&
            $rel["estado"] === "pendiente"
        ) {
            $estado_relacion = "pendiente_recibida";
        }
    }

    $stmt->close();
}


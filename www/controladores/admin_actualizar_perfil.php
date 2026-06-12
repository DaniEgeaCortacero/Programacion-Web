<?php
session_start();
require_once __DIR__ . "/admin_guard.php";
require_once __DIR__ . "/db.php";

$id_usuario = intval($_POST["id_usuario"] ?? 0);

$usuario = trim($_POST["usuario"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$fecha_nacimiento = $_POST["fecha_nacimiento"] ?? "";
$id_tipo_actividad = intval($_POST["tipo_actividad"] ?? 0);

if (
    $id_usuario <= 0 ||
    $usuario === "" ||
    $nombre === "" ||
    $apellidos === "" ||
    $fecha_nacimiento === "" ||
    $id_tipo_actividad <= 0
) {
    exit("Datos incompletos");
}

$sql_check = "SELECT id
              FROM usuario
              WHERE usuario = ?
              AND id != ?
              LIMIT 1";

$stmt = $mysqli->prepare($sql_check);
$stmt->bind_param("si", $usuario, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $stmt->close();
    exit("Ese nombre de usuario ya existe");
}

$stmt->close();

$sql = "UPDATE usuario
        SET usuario = ?,
            nombre = ?,
            apellidos = ?,
            fecha_nacimiento = ?,
            id_tipo_actividad_preferida = ?
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param(
    "ssssii",
    $usuario,
    $nombre,
    $apellidos,
    $fecha_nacimiento,
    $id_tipo_actividad,
    $id_usuario
);

if (!$stmt->execute()) {
    $stmt->close();
    exit("Error al actualizar el usuario");
}

$stmt->close();

header("Location: ../html/prototipo_main.php?vista=admin_usuarios");
exit;
?>
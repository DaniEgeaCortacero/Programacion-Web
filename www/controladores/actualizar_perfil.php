<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../html/prototipo_login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

$usuario = trim($_POST["usuario"] ?? "");
$nombre = trim($_POST["nombre"] ?? "");
$apellidos = trim($_POST["apellidos"] ?? "");
$fecha_nacimiento = $_POST["fecha_nacimiento"] ?? "";
$id_tipo_actividad = $_POST["tipo_actividad"] ?? null;

if (
    $usuario === "" ||
    $nombre === "" ||
    $apellidos === "" ||
    $fecha_nacimiento === "" ||
    empty($id_tipo_actividad)
) {
    echo "<script>
        alert('Debes rellenar todos los campos obligatorios.');
        window.location.href='../html/prototipo_main.php?vista=perfil';
    </script>";
    exit;
}

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


if ($stmt->execute()) {
    $_SESSION["usuarioactual"] = $usuario;

    header("Location: ../html/prototipo_main.php?vista=perfil");
    exit;
} else {
    echo "<script>
        alert('Error al actualizar el perfil.');
        window.location.href='../html/prototipo_main.php?vista=perfil';
    </script>";
    exit;
}

$stmt->close();
$mysqli->close();
?>
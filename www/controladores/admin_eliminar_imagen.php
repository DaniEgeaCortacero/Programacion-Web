<?php
session_start();
require_once __DIR__ . "/admin_guard.php";
require_once __DIR__ . "/db.php";

$id_imagen = intval($_POST["id_imagen"] ?? 0);
$id_usuario = intval($_POST["id_usuario"] ?? 0);

if ($id_imagen <= 0 || $id_usuario <= 0) {
    exit("Imagen no válida");
}

/*
    No eliminamos imágenes de perfil desde aquí para evitar dejar al usuario sin perfil.
*/
$sql_check = "SELECT ruta, es_perfil
              FROM imagen
              WHERE id = ?
              AND id_usuario = ?
              LIMIT 1";

$stmt = $mysqli->prepare($sql_check);
$stmt->bind_param("ii", $id_imagen, $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    exit("Imagen no encontrada");
}

$img = $res->fetch_assoc();
$stmt->close();

if (intval($img["es_perfil"]) === 1) {
    exit("No se puede eliminar la imagen de perfil desde aquí");
}

/*
    Borramos primero relaciones con actividades si existen.
*/
$sql_rel = "DELETE FROM actividad_imagen
            WHERE id_imagen = ?";

$stmt = $mysqli->prepare($sql_rel);
$stmt->bind_param("i", $id_imagen);
$stmt->execute();
$stmt->close();

/*
    Borrar registro de imagen.
*/
$sql = "DELETE FROM imagen
        WHERE id = ?
        AND id_usuario = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $id_imagen, $id_usuario);

if (!$stmt->execute()) {
    exit("Error al eliminar imagen");
}

$stmt->close();

/*
    Opcional: borrar archivo físico si existe.
    Convertimos ../img/... a ruta absoluta dentro del proyecto.
*/
$ruta_relativa = $img["ruta"];
$ruta_fisica = realpath(__DIR__ . "/../html/" . $ruta_relativa);

if ($ruta_fisica && file_exists($ruta_fisica)) {
    @unlink($ruta_fisica);
}

header("Location: ../html/prototipo_main.php?vista=admin_imagenes&id_usuario=" . $id_usuario);
exit;
?>
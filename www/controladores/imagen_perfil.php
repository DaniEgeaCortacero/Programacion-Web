<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../html/prototipo_login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

if (!isset($_FILES["imagen_perfil"]) || $_FILES["imagen_perfil"]["error"] !== UPLOAD_ERR_OK) {
    header("Location: ../html/prototipo_main.php?vista=perfil");
    exit;
}

$archivo = $_FILES["imagen_perfil"];
$nombre_original = $archivo["name"];
$tmp = $archivo["tmp_name"];
$tamano = $archivo["size"];

$info = getimagesize($tmp);
if ($info === false) {
    header("Location: ../html/prototipo_main.php?vista=perfil");
    exit;
}

$ancho = $info[0];
$alto = $info[1];

$extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
$permitidas = ["jpg", "jpeg", "png", "webp"];

if (!in_array($extension, $permitidas)) {
    header("Location: ../html/prototipo_main.php?vista=perfil");
    exit;
}

$nombre_final = "perfil_" . $id_usuario . "_" . time() . "." . $extension;

$carpeta = __DIR__ . "/../img/perfiles/";
$ruta_fisica = $carpeta . $nombre_final;
$ruta_bd = "../img/perfiles/" . $nombre_final;

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

if (!move_uploaded_file($tmp, $ruta_fisica)) {
    header("Location: ../html/prototipo_main.php?vista=perfil");
    exit;
}

$sql = "UPDATE imagen SET es_perfil = 0 WHERE id_usuario = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->close();

$sql = "INSERT INTO imagen (id_usuario, nombre, tamano, alto, ancho, ruta, es_perfil)
        VALUES (?, ?, ?, ?, ?, ?, 1)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("isiiis", $id_usuario, $nombre_final, $tamano, $alto, $ancho, $ruta_bd);
$stmt->execute();
$stmt->close();

header("Location: ../html/prototipo_main.php?vista=perfil");
exit;
?>
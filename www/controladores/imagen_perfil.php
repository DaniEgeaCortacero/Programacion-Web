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

$hash = md5_file($tmp);
$nombre_final = "perfil_" . $id_usuario . "_" . $hash . "." . $extension;

$carpeta = __DIR__ . "/../img/perfiles/";
$ruta_fisica = $carpeta . $nombre_final;
$ruta_bd = "../img/perfiles/" . $nombre_final;

if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

if (!file_exists($ruta_fisica)) {

    if (!move_uploaded_file($tmp, $ruta_fisica)) {
        header("Location: ../html/prototipo_main.php?vista=perfil");
        exit;
    }
}

// Buscar si ya tiene imagen de perfil
$sql_check = "SELECT id, ruta 
              FROM imagen 
              WHERE id_usuario = ? 
              AND es_perfil = 1
              LIMIT 1";

$stmt_check = $mysqli->prepare($sql_check);
$stmt_check->bind_param("i", $id_usuario);
$stmt_check->execute();

$resultado = $stmt_check->get_result();

if ($resultado->num_rows > 0) { // Si existe imágen

    $imagen_actual = $resultado->fetch_assoc();

    $ruta_vieja = __DIR__ . "/../" . $imagen_actual["ruta"];

    if ($ruta_vieja !== $ruta_fisica && file_exists($ruta_vieja)) {
        unlink($ruta_vieja);
    }

    $sql_update = "UPDATE imagen
                   SET nombre = ?,
                       tamano = ?,
                       alto = ?,
                       ancho = ?,
                       ruta = ?
                   WHERE id = ?";

    $stmt_update = $mysqli->prepare($sql_update);

    $stmt_update->bind_param(
        "siiisi",
        $nombre_final,
        $tamano,
        $alto,
        $ancho,
        $ruta_bd,
        $imagen_actual["id"]
    );

    $stmt_update->execute();
    $stmt_update->close();

} else { // Si no existe imagen

    $sql_insert = "INSERT INTO imagen
        (id_usuario, nombre, tamano, alto, ancho, ruta, es_perfil)
        VALUES (?, ?, ?, ?, ?, ?, 1)";

    $stmt_insert = $mysqli->prepare($sql_insert);

    $stmt_insert->bind_param(
        "isiiis",
        $id_usuario,
        $nombre_final,
        $tamano,
        $alto,
        $ancho,
        $ruta_bd
    );

    $stmt_insert->execute();
    $stmt_insert->close();
}

$stmt_check->close();

header("Location: ../html/prototipo_main.php?vista=perfil");
exit;
?>
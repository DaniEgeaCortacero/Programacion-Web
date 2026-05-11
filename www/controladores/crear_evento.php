<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../html/prototipo_login.php");
    exit;
}

$id_usuario = $_SESSION["id_usuario"];

$titulo = trim($_POST["titulo"] ?? "");
$id_tipo = intval($_POST["tipo"] ?? 0);
$descripcion = trim($_POST["descripcion"] ?? "");

$fecha = $_POST["fecha"] ?? "";
$hora = $_POST["hora"] ?? "00:00";

$id_pais = intval($_POST["pais"] ?? 0);
$id_provincia = is_numeric($_POST["provincia"] ?? null) ? intval($_POST["provincia"]) : null;
$id_localidad = is_numeric($_POST["localidad"] ?? null) ? intval($_POST["localidad"]) : null;

if ($titulo === "" || $id_tipo <= 0 || $fecha === "" || $id_pais <= 0) {
    echo "<script>
        alert('Debes rellenar los campos obligatorios.');
        window.history.back();
    </script>";
    exit;
}

$fecha_evento = $fecha . " " . $hora . ":00";

/* GPX */
$ruta_gpx = null;

if (isset($_FILES["gpx_ruta"]) && $_FILES["gpx_ruta"]["error"] === UPLOAD_ERR_OK) {
    $tmp = $_FILES["gpx_ruta"]["tmp_name"];
    $nombre_original = $_FILES["gpx_ruta"]["name"];
    $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

    if ($extension === "gpx") {
        $nombre_final = "gpx_" . $id_usuario . "_" . time() . ".gpx";
        $carpeta = __DIR__ . "/../gpx/";

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $ruta_fisica = $carpeta . $nombre_final;
        $ruta_gpx = "../gpx/" . $nombre_final;

        move_uploaded_file($tmp, $ruta_fisica);
    }
}

/* INSERT ACTIVIDAD */
$sql = "INSERT INTO actividad (
            id_usuario,
            titulo,
            descripcion,
            id_tipo_actividad,
            fecha_evento,
            id_pais,
            id_provincia,
            id_localidad,
            archivo_gpx
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param(
    "issisiiis",
    $id_usuario,
    $titulo,
    $descripcion,
    $id_tipo,
    $fecha_evento,
    $id_pais,
    $id_provincia,
    $id_localidad,
    $ruta_gpx
);

$stmt->execute();
$id_actividad = $stmt->insert_id;
$stmt->close();

/* IMÁGENES */
if (isset($_FILES["imagenes"])) {
    $total = count($_FILES["imagenes"]["name"]);

    for ($i = 0; $i < $total; $i++) {
        if ($_FILES["imagenes"]["error"][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmp = $_FILES["imagenes"]["tmp_name"][$i];
        $nombre_original = $_FILES["imagenes"]["name"][$i];
        $tamano = $_FILES["imagenes"]["size"][$i];

        $info = getimagesize($tmp);
        if ($info === false) {
            continue;
        }

        $ancho = $info[0];
        $alto = $info[1];

        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($extension, $permitidas)) {
            continue;
        }

        $hash = md5_file($tmp);
        $nombre_final = "actividad_" . $id_actividad . "_" . $hash . "." . $extension;

        $carpeta = __DIR__ . "/../img/actividades/";

        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $ruta_fisica = $carpeta . $nombre_final;
        $ruta_bd = "../img/actividades/" . $nombre_final;

        if (!file_exists($ruta_fisica)) {
            move_uploaded_file($tmp, $ruta_fisica);
        }

        $sql_img = "INSERT INTO imagen (
                        id_usuario,
                        nombre,
                        tamano,
                        alto,
                        ancho,
                        ruta,
                        es_perfil
                    ) VALUES (?, ?, ?, ?, ?, ?, 0)";

        $stmt_img = $mysqli->prepare($sql_img);
        $stmt_img->bind_param(
            "isiiis",
            $id_usuario,
            $nombre_final,
            $tamano,
            $alto,
            $ancho,
            $ruta_bd
        );

        $stmt_img->execute();
        $id_imagen = $stmt_img->insert_id;
        $stmt_img->close();

        $sql_rel = "INSERT INTO actividad_imagen (id_actividad, id_imagen)
                    VALUES (?, ?)";

        $stmt_rel = $mysqli->prepare($sql_rel);
        $stmt_rel->bind_param("ii", $id_actividad, $id_imagen);
        $stmt_rel->execute();
        $stmt_rel->close();
    }
}

header("Location: ../html/prototipo_main.php?vista=perfil");
exit;
?>
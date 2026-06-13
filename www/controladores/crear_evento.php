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

/* COMPAÑEROS */
$companeros = $_POST["companeros"] ?? [];

if (!is_array($companeros)) {
    $companeros = [];
}

$sql_companero = "INSERT IGNORE INTO actividad_companero 
                  (id_actividad, id_usuario)
                  SELECT ?, u.id
                  FROM usuario u
                  JOIN amistad a 
                    ON a.id_amigo = u.id
                    AND a.id_usuario = ?
                    AND a.estado = 'aceptada'
                  WHERE u.id = ?";

$stmt_companero = $mysqli->prepare($sql_companero);

foreach ($companeros as $id_companero) {
    $id_companero = intval($id_companero);

    if ($id_companero <= 0 || $id_companero == $id_usuario) {
        continue;
    }

    $stmt_companero->bind_param(
        "iii",
        $id_actividad,
        $id_usuario,
        $id_companero
    );

    $stmt_companero->execute();
}

$stmt_companero->close();



/* IMÁGENES */
/* IMÁGENES DE LA ACTIVIDAD */
if (isset($_FILES["imagenes"]) && !empty($_FILES["imagenes"]["name"][0])) {

    $carpeta_destino = __DIR__ . "/../img/actividades/";

    if (!is_dir($carpeta_destino)) {
        if (!mkdir($carpeta_destino, 0777, true)) {
            die("No se pudo crear la carpeta: " . $carpeta_destino);
        }
    }

    if (!is_writable($carpeta_destino)) {
        die("La carpeta no tiene permisos de escritura: " . $carpeta_destino);
    }

    $extensiones_permitidas = ["jpg", "jpeg", "png", "webp"];
    $mimes_permitidos = ["image/jpeg", "image/png", "image/webp"];

    for ($i = 0; $i < count($_FILES["imagenes"]["name"]); $i++) {

        if ($_FILES["imagenes"]["error"][$i] !== UPLOAD_ERR_OK) {
            continue;
        }

        $nombre_original = $_FILES["imagenes"]["name"][$i];
        $tmp = $_FILES["imagenes"]["tmp_name"][$i];
        $tamano = intval($_FILES["imagenes"]["size"][$i]);

        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

        if (!in_array($extension, $extensiones_permitidas)) {
            continue;
        }

        $info_imagen = getimagesize($tmp);

        if ($info_imagen === false) {
            continue;
        }

        $ancho = intval($info_imagen[0]);
        $alto = intval($info_imagen[1]);
        $mime = $info_imagen["mime"];

        if (!in_array($mime, $mimes_permitidos)) {
            continue;
        }

        /*
            Nombre limpio y único.
            Evita problemas con tildes, espacios, paréntesis o nombres repetidos.
        */
        if ($extension === "jpeg") {
            $extension = "jpg";
        }

        $nombre_archivo = "actividad_" . $id_actividad . "_" . uniqid() . "." . $extension;

        $ruta_fisica = $carpeta_destino . $nombre_archivo;
        $ruta_bd = "../img/actividades/" . $nombre_archivo;

        if (!move_uploaded_file($tmp, $ruta_fisica)) {
            echo "<script>
                alert('No se pudo guardar una de las imágenes. Revisa permisos de la carpeta img/actividades.');
                window.history.back();
            </script>";
            exit;
        }

        /* Insertar imagen */
        $es_perfil = 0;

        $sql_img = "INSERT INTO imagen 
                    (id_usuario, nombre, tamano, alto, ancho, ruta, es_perfil)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt_img = $mysqli->prepare($sql_img);

        if (!$stmt_img) {
            die("Error preparando imagen: " . $mysqli->error);
        }

        $stmt_img->bind_param(
            "isiiisi",
            $id_usuario,
            $nombre_original,
            $tamano,
            $alto,
            $ancho,
            $ruta_bd,
            $es_perfil
        );

        if (!$stmt_img->execute()) {
            die("Error guardando imagen: " . $stmt_img->error);
        }

        $id_imagen = $stmt_img->insert_id;
        $stmt_img->close();

        /* Relacionar imagen con actividad */
        $sql_rel = "INSERT INTO actividad_imagen 
                    (id_actividad, id_imagen)
                    VALUES (?, ?)";

        $stmt_rel = $mysqli->prepare($sql_rel);

        if (!$stmt_rel) {
            die("Error preparando relación imagen: " . $mysqli->error);
        }

        $stmt_rel->bind_param("ii", $id_actividad, $id_imagen);

        if (!$stmt_rel->execute()) {
            die("Error relacionando imagen con actividad: " . $stmt_rel->error);
        }

        $stmt_rel->close();
    }
}

header("Location: ../html/prototipo_main.php?vista=perfil");
exit;
?>
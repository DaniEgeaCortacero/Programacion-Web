<?php
session_start();
require_once __DIR__ . "/db.php";

function volverConError($mensaje) {
    echo "<script>
        alert(" . json_encode($mensaje) . ");
        window.history.back();
    </script>";
    exit;
}

function mensajeErrorUpload($codigo) {
    $errores = [
        UPLOAD_ERR_INI_SIZE => "El archivo supera el tamaño máximo permitido por PHP.",
        UPLOAD_ERR_FORM_SIZE => "El archivo supera el tamaño máximo permitido por el formulario.",
        UPLOAD_ERR_PARTIAL => "El archivo se subió parcialmente.",
        UPLOAD_ERR_NO_FILE => "No se subió ningún archivo.",
        UPLOAD_ERR_NO_TMP_DIR => "Falta la carpeta temporal de PHP.",
        UPLOAD_ERR_CANT_WRITE => "No se pudo escribir el archivo en disco.",
        UPLOAD_ERR_EXTENSION => "Una extensión de PHP bloqueó la subida."
    ];

    return $errores[$codigo] ?? "Error desconocido al subir el archivo.";
}

if (!isset($_SESSION["id_usuario"])) {
    volverConError("No autenticado");
}

$id_usuario = intval($_SESSION["id_usuario"]);
$id_actividad = intval($_POST["id_actividad"] ?? 0);

$titulo = trim($_POST["titulo"] ?? "");
$descripcion = trim($_POST["descripcion"] ?? "");
$id_tipo_actividad = intval($_POST["tipo"] ?? 0);

$id_pais = intval($_POST["pais"] ?? 0);

$id_provincia = is_numeric($_POST["provincia"] ?? null)
    ? intval($_POST["provincia"])
    : null;

$id_localidad = is_numeric($_POST["localidad"] ?? null)
    ? intval($_POST["localidad"])
    : null;

if ($id_provincia <= 0) {
    $id_provincia = null;
}

if ($id_localidad <= 0) {
    $id_localidad = null;
}

$fecha = $_POST["fecha"] ?? "";
$hora = $_POST["hora"] ?? "";

if (
    $id_actividad <= 0 ||
    $titulo === "" ||
    $id_tipo_actividad <= 0 ||
    $fecha === "" ||
    $hora === "" ||
    $id_pais <= 0
) {
    volverConError("Datos incompletos");
}

$fecha_evento = $fecha . " " . $hora . ":00";

$es_admin = isset($_SESSION["id_rol"]) && intval($_SESSION["id_rol"]) === 1;


/* =======================
   DUEÑO REAL DE ACTIVIDAD
   ======================= */

if ($es_admin) {
    $sql_owner = "SELECT id_usuario FROM actividad WHERE id = ? LIMIT 1";
    $stmt_owner = $mysqli->prepare($sql_owner);
    $stmt_owner->bind_param("i", $id_actividad);
} else {
    $sql_owner = "SELECT id_usuario FROM actividad WHERE id = ? AND id_usuario = ? LIMIT 1";
    $stmt_owner = $mysqli->prepare($sql_owner);
    $stmt_owner->bind_param("ii", $id_actividad, $id_usuario);
}

if (!$stmt_owner->execute()) {
    volverConError("Error comprobando permisos: " . $stmt_owner->error);
}

$res_owner = $stmt_owner->get_result();

if ($res_owner->num_rows === 0) {
    volverConError("No tienes permiso para editar esta actividad");
}

$fila_owner = $res_owner->fetch_assoc();
$id_usuario_actividad = intval($fila_owner["id_usuario"]);

$stmt_owner->close();


/* =======================
   GPX NUEVO EN EDICIÓN
   ======================= */

$ruta_gpx = null;

if (
    isset($_FILES["gpx_ruta"]) &&
    $_FILES["gpx_ruta"]["error"] !== UPLOAD_ERR_NO_FILE
) {
    if ($_FILES["gpx_ruta"]["error"] !== UPLOAD_ERR_OK) {
        volverConError("Error subiendo GPX: " . mensajeErrorUpload($_FILES["gpx_ruta"]["error"]));
    }

    $tmp = $_FILES["gpx_ruta"]["tmp_name"];
    $nombre_original = $_FILES["gpx_ruta"]["name"];
    $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

    if ($extension !== "gpx") {
        volverConError("El archivo de ruta debe tener extensión .gpx");
    }

    if (!is_uploaded_file($tmp)) {
        volverConError("El archivo GPX temporal no es válido.");
    }

    $carpeta_gpx = __DIR__ . "/../gpx/";

    if (!is_dir($carpeta_gpx)) {
        if (!mkdir($carpeta_gpx, 0777, true)) {
            volverConError("No se pudo crear la carpeta GPX.");
        }
    }

    if (!is_writable($carpeta_gpx)) {
        volverConError("La carpeta GPX no tiene permisos de escritura.");
    }

    $nombre_final = "gpx_" . $id_usuario_actividad . "_" . time() . "_" . uniqid() . ".gpx";
    $ruta_fisica = $carpeta_gpx . $nombre_final;
    $ruta_gpx = "../gpx/" . $nombre_final;

    if (!move_uploaded_file($tmp, $ruta_fisica)) {
        volverConError("No se pudo guardar el archivo GPX.");
    }
}


/* =======================
   ACTUALIZAR ACTIVIDAD
   ======================= */

if ($es_admin) {

    if ($ruta_gpx !== null) {
        $sql = "UPDATE actividad
                SET titulo = ?,
                    descripcion = ?,
                    id_tipo_actividad = ?,
                    fecha_evento = ?,
                    id_pais = ?,
                    id_provincia = ?,
                    id_localidad = ?,
                    archivo_gpx = ?
                WHERE id = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "ssisiiisi",
            $titulo,
            $descripcion,
            $id_tipo_actividad,
            $fecha_evento,
            $id_pais,
            $id_provincia,
            $id_localidad,
            $ruta_gpx,
            $id_actividad
        );
    } else {
        $sql = "UPDATE actividad
                SET titulo = ?,
                    descripcion = ?,
                    id_tipo_actividad = ?,
                    fecha_evento = ?,
                    id_pais = ?,
                    id_provincia = ?,
                    id_localidad = ?
                WHERE id = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "ssisiiii",
            $titulo,
            $descripcion,
            $id_tipo_actividad,
            $fecha_evento,
            $id_pais,
            $id_provincia,
            $id_localidad,
            $id_actividad
        );
    }

} else {

    if ($ruta_gpx !== null) {
        $sql = "UPDATE actividad
                SET titulo = ?,
                    descripcion = ?,
                    id_tipo_actividad = ?,
                    fecha_evento = ?,
                    id_pais = ?,
                    id_provincia = ?,
                    id_localidad = ?,
                    archivo_gpx = ?
                WHERE id = ?
                AND id_usuario = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "ssisiiisii",
            $titulo,
            $descripcion,
            $id_tipo_actividad,
            $fecha_evento,
            $id_pais,
            $id_provincia,
            $id_localidad,
            $ruta_gpx,
            $id_actividad,
            $id_usuario
        );
    } else {
        $sql = "UPDATE actividad
                SET titulo = ?,
                    descripcion = ?,
                    id_tipo_actividad = ?,
                    fecha_evento = ?,
                    id_pais = ?,
                    id_provincia = ?,
                    id_localidad = ?
                WHERE id = ?
                AND id_usuario = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param(
            "ssisiiiii",
            $titulo,
            $descripcion,
            $id_tipo_actividad,
            $fecha_evento,
            $id_pais,
            $id_provincia,
            $id_localidad,
            $id_actividad,
            $id_usuario
        );
    }
}

if (!$stmt->execute()) {
    volverConError("Error al actualizar actividad: " . $stmt->error);
}

$stmt->close();


/* =======================
   ACTUALIZAR COMPAÑEROS
   ======================= */

$sql_delete = "DELETE FROM actividad_companero
               WHERE id_actividad = ?";

$stmt = $mysqli->prepare($sql_delete);
$stmt->bind_param("i", $id_actividad);

if (!$stmt->execute()) {
    volverConError("Error eliminando compañeros anteriores: " . $stmt->error);
}

$stmt->close();

$companeros = $_POST["companeros"] ?? [];

if (!is_array($companeros)) {
    $companeros = [];
}

$sql_comp = "INSERT IGNORE INTO actividad_companero 
             (id_actividad, id_usuario)
             SELECT ?, u.id
             FROM usuario u
             JOIN amistad a 
                ON a.id_amigo = u.id
                AND a.id_usuario = ?
                AND a.estado = 'aceptada'
             WHERE u.id = ?";

$stmt = $mysqli->prepare($sql_comp);

if (!$stmt) {
    volverConError("Error preparando compañeros: " . $mysqli->error);
}

foreach ($companeros as $id_companero) {
    $id_companero = intval($id_companero);

    if ($id_companero <= 0 || $id_companero == $id_usuario_actividad) {
        continue;
    }

    /*
        Usamos $id_usuario_actividad, no $id_usuario,
        para que si edita un admin, se validen los amigos del dueño real.
    */
    $stmt->bind_param(
        "iii",
        $id_actividad,
        $id_usuario_actividad,
        $id_companero
    );

    if (!$stmt->execute()) {
        volverConError("Error guardando compañero: " . $stmt->error);
    }
}

$stmt->close();


/* =======================
   IMÁGENES NUEVAS EN EDICIÓN
   ======================= */

if (isset($_FILES["imagenes"]) && !empty($_FILES["imagenes"]["name"][0])) {

    $carpeta_destino = __DIR__ . "/../img/actividades/";

    if (!is_dir($carpeta_destino)) {
        if (!mkdir($carpeta_destino, 0777, true)) {
            volverConError("No se pudo crear la carpeta de imágenes.");
        }
    }

    if (!is_writable($carpeta_destino)) {
        volverConError("La carpeta de imágenes no tiene permisos de escritura.");
    }

    $extensiones_permitidas = ["jpg", "jpeg", "png", "webp"];
    $mimes_permitidos = ["image/jpeg", "image/png", "image/webp"];

    $tamano_maximo = 5 * 1024 * 1024; // 5 MB por imagen
    $resolucion_maxima = 5000;        // 5000 px por lado

    $total_imagenes = count($_FILES["imagenes"]["name"]);

    for ($i = 0; $i < $total_imagenes; $i++) {

        $nombre_original = $_FILES["imagenes"]["name"][$i] ?? "";
        $tmp = $_FILES["imagenes"]["tmp_name"][$i] ?? "";
        $error = $_FILES["imagenes"]["error"][$i] ?? UPLOAD_ERR_NO_FILE;
        $tamano = intval($_FILES["imagenes"]["size"][$i] ?? 0);

        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($error !== UPLOAD_ERR_OK) {
            volverConError(
                "Error subiendo la imagen " . $nombre_original . ": " .
                mensajeErrorUpload($error)
            );
        }

        if (!is_uploaded_file($tmp)) {
            volverConError("El archivo temporal no es válido: " . $nombre_original);
        }

        if ($tamano <= 0) {
            volverConError("La imagen " . $nombre_original . " está vacía.");
        }

        if ($tamano > $tamano_maximo) {
            volverConError(
                "La imagen " . $nombre_original .
                " pesa demasiado: " . round($tamano / 1024 / 1024, 2) .
                " MB. Máximo permitido: 5 MB."
            );
        }

        $extension = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));

        if ($extension === "jpeg") {
            $extension = "jpg";
        }

        if (!in_array($extension, $extensiones_permitidas)) {
            volverConError(
                "Extensión no permitida en " . $nombre_original .
                ". Formatos permitidos: JPG, PNG y WEBP."
            );
        }

        $info_imagen = getimagesize($tmp);

        if ($info_imagen === false) {
            volverConError("No se pudo leer la imagen: " . $nombre_original);
        }

        $ancho = intval($info_imagen[0]);
        $alto = intval($info_imagen[1]);
        $mime = $info_imagen["mime"] ?? "";

        if (!in_array($mime, $mimes_permitidos)) {
            volverConError(
                "Tipo MIME no permitido en " . $nombre_original .
                ". Tipo detectado: " . $mime
            );
        }

        if ($ancho > $resolucion_maxima || $alto > $resolucion_maxima) {
            volverConError(
                "La imagen " . $nombre_original .
                " tiene demasiada resolución: " . $ancho . "x" . $alto .
                ". Máximo permitido: " . $resolucion_maxima . "px por lado."
            );
        }

        $nombre_archivo = "actividad_" . $id_actividad . "_" . uniqid("", true) . "." . $extension;

        $ruta_fisica = $carpeta_destino . $nombre_archivo;
        $ruta_bd = "../img/actividades/" . $nombre_archivo;

        if (!move_uploaded_file($tmp, $ruta_fisica)) {
            volverConError(
                "No se pudo guardar la imagen " . $nombre_original .
                " en la carpeta de actividades."
            );
        }

        $es_perfil = 0;

        $sql_img = "INSERT INTO imagen 
                    (id_usuario, nombre, tamano, alto, ancho, ruta, es_perfil)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt_img = $mysqli->prepare($sql_img);

        if (!$stmt_img) {
            volverConError("Error preparando imagen: " . $mysqli->error);
        }

        $stmt_img->bind_param(
            "isiiisi",
            $id_usuario_actividad,
            $nombre_original,
            $tamano,
            $alto,
            $ancho,
            $ruta_bd,
            $es_perfil
        );

        if (!$stmt_img->execute()) {
            volverConError("Error guardando imagen: " . $stmt_img->error);
        }

        $id_imagen = $stmt_img->insert_id;
        $stmt_img->close();

        $sql_rel = "INSERT INTO actividad_imagen 
                    (id_actividad, id_imagen)
                    VALUES (?, ?)";

        $stmt_rel = $mysqli->prepare($sql_rel);

        if (!$stmt_rel) {
            volverConError("Error preparando relación imagen: " . $mysqli->error);
        }

        $stmt_rel->bind_param("ii", $id_actividad, $id_imagen);

        if (!$stmt_rel->execute()) {
            volverConError("Error relacionando imagen con actividad: " . $stmt_rel->error);
        }

        $stmt_rel->close();
    }
}


/* =======================
   REDIRECCIÓN FINAL
   ======================= */

if ($es_admin) {
    header("Location: ../html/prototipo_main.php?vista=admin_actividades");
} else {
    header("Location: ../html/prototipo_main.php?vista=perfil");
}

exit;
?>
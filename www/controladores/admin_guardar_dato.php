<?php
session_start();
require_once __DIR__ . "/admin_guard.php";
require_once __DIR__ . "/db.php";

$seccion = $_POST["seccion"] ?? "";
$nombre = trim($_POST["nombre"] ?? "");

if ($nombre === "") {
    exit("Nombre obligatorio");
}

function siguienteIdProvincia($mysqli) {
    $sql = "SELECT COALESCE(MAX(id), 0) + 1 AS siguiente FROM provincia";
    $res = $mysqli->query($sql);
    $fila = $res->fetch_assoc();

    return intval($fila["siguiente"]);
}

if ($seccion === "tipos") {

    $sql = "INSERT INTO tipo_actividad (nombre) VALUES (?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $nombre);

} elseif ($seccion === "paises") {

    $iso = strtoupper(trim($_POST["iso"] ?? ""));

    if ($iso === "") {
        exit("ISO obligatorio");
    }

    $sql = "INSERT INTO pais (iso, nombre) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $iso, $nombre);

} elseif ($seccion === "provincias") {

    $id_pais = intval($_POST["id_pais"] ?? 0);
    $id_ccaa = intval($_POST["id_ccaa"] ?? 0);

    if ($id_pais <= 0) {
        exit("País obligatorio");
    }

    $id = siguienteIdProvincia($mysqli);

    $sql = "INSERT INTO provincia (
                id,
                id_ccaa,
                nombre,
                id_pais
            ) VALUES (?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iisi", $id, $id_ccaa, $nombre, $id_pais);

} elseif ($seccion === "localidades") {

    $id_provincia = intval($_POST["id_provincia"] ?? 0);

    if ($id_provincia <= 0) {
        exit("Provincia obligatoria");
    }

    
    $cod_municipio = intval($_POST["cod_municipio"] ?? 0);
    $dc = intval($_POST["dc"] ?? 0);

    $sql = "INSERT INTO localidad (
                id_provincia,
                cod_municipio,
                dc,
                nombre
            ) VALUES (?, ?, ?, ?)";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iiis", $id_provincia, $cod_municipio, $dc, $nombre);

} else {
    exit("Sección no válida");
}

if (!$stmt->execute()) {
    exit("Error al guardar el dato: " . $stmt->error);
}

$stmt->close();

header("Location: ../html/prototipo_main.php?vista=admin_datos&seccion=" . urlencode($seccion));
exit;
?>
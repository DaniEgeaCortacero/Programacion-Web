<?php
session_start();
require_once __DIR__ . "/admin_guard.php";
require_once __DIR__ . "/db.php";

$seccion = $_POST["seccion"] ?? "";
$id = intval($_POST["id"] ?? 0);
$nombre = trim($_POST["nombre"] ?? "");

if ($id <= 0 || $nombre === "") {
    exit("Datos incompletos");
}

if ($seccion === "tipos") {

    $sql = "UPDATE tipo_actividad
            SET nombre = ?
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $nombre, $id);

} elseif ($seccion === "paises") {

    $iso = strtoupper(trim($_POST["iso"] ?? ""));

    if ($iso === "") {
        exit("ISO obligatorio");
    }

    $sql = "UPDATE pais
            SET nombre = ?,
                iso = ?
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssi", $nombre, $iso, $id);

} elseif ($seccion === "provincias") {

    $id_pais = intval($_POST["id_pais"] ?? 0);
    $id_ccaa = intval($_POST["id_ccaa"] ?? 0);

    if ($id_pais <= 0) {
        exit("País obligatorio");
    }

    $sql = "UPDATE provincia
            SET nombre = ?,
                id_pais = ?,
                id_ccaa = ?
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("siii", $nombre, $id_pais, $id_ccaa, $id);

} elseif ($seccion === "localidades") {

    $id_provincia = intval($_POST["id_provincia"] ?? 0);
    $cod_municipio = intval($_POST["cod_municipio"] ?? 0);
    $dc = intval($_POST["dc"] ?? 0);

    if ($id_provincia <= 0) {
        exit("Provincia obligatoria");
    }

    $sql = "UPDATE localidad
            SET nombre = ?,
                id_provincia = ?,
                cod_municipio = ?,
                dc = ?
            WHERE id = ?";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param(
        "siiii",
        $nombre,
        $id_provincia,
        $cod_municipio,
        $dc,
        $id
    );

} else {
    exit("Sección no válida");
}

if (!$stmt->execute()) {
    exit("Error al actualizar dato: " . $stmt->error);
}

$stmt->close();

header("Location: ../html/prototipo_main.php?vista=admin_datos&seccion=" . urlencode($seccion));
exit;
?>
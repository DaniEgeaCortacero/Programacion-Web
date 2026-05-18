<?php
session_start();
require_once "../modelo/conexion.php";

if (!isset($_SESSION["id_usuario"])) {
    exit("Debes iniciar sesión");
}

$id_usuario = $_SESSION["id_usuario"];
$id_amigo = $_POST["id_amigo"] ?? null;

if (!$id_amigo || $id_usuario == $id_amigo) {
    exit("Usuario no válido");
}

/*
-----------------------------------
1. ¿Ya existe relación directa?
-----------------------------------
*/
$sql = "SELECT estado
        FROM amistad
        WHERE id_usuario = ? AND id_amigo = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_amigo);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0) {

    $fila = $res->fetch_assoc();

    if ($fila["estado"] == "pendiente") {
        exit("Solicitud ya enviada");
    }

    if ($fila["estado"] == "aceptada") {
        exit("Ya sois amigos");
    }
}

/*
-----------------------------------
2. ¿La otra persona ya me seguía?
-----------------------------------
*/
$sql = "SELECT estado
        FROM amistad
        WHERE id_usuario = ? AND id_amigo = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $id_amigo, $id_usuario);
$stmt->execute();

$res = $stmt->get_result();

if ($res->num_rows > 0) {

    $fila = $res->fetch_assoc();

    if ($fila["estado"] == "pendiente") {

        /*
        -----------------------------------
        3. Convertir en amistad
        -----------------------------------
        */

        // Actualizar solicitud original
        $sql_update = "UPDATE amistad
                       SET estado = 'aceptada'
                       WHERE id_usuario = ? AND id_amigo = ?";

        $stmt = $conexion->prepare($sql_update);
        $stmt->bind_param("ii", $id_amigo, $id_usuario);
        $stmt->execute();

        // Crear relación inversa aceptada
        $sql_insert = "INSERT INTO amistad
                       (id_usuario, id_amigo, estado)
                       VALUES (?, ?, 'aceptada')";

        $stmt = $conexion->prepare($sql_insert);
        $stmt->bind_param("ii", $id_usuario, $id_amigo);
        $stmt->execute();

        exit("Ahora sois amigos");
    }
}

/*
-----------------------------------
4. Crear solicitud pendiente
-----------------------------------
*/

$sql_insert = "INSERT INTO amistad
               (id_usuario, id_amigo)
               VALUES (?, ?)";

$stmt = $conexion->prepare($sql_insert);
$stmt->bind_param("ii", $id_usuario, $id_amigo);

if ($stmt->execute()) {
    echo "Solicitud enviada";
} else {
    echo "Error";
}
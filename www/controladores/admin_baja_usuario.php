<?php
session_start();
require_once __DIR__ . "/admin_guard.php";
require_once __DIR__ . "/db.php";

$id_usuario = intval($_POST["id_usuario"] ?? 0);

if ($id_usuario <= 0) {
    exit("Usuario no válido");
}

$sql = "UPDATE usuario
        SET fecha_baja = NOW()
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->close();

header("Location: ../html/prototipo_main.php?vista=admin_usuarios");
exit;
?>
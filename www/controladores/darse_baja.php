<?php
session_start();
require_once __DIR__ . "/db.php";

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../html/prototipo_login.php");
    exit;
}

$id_usuario = intval($_SESSION["id_usuario"]);

$sql = "UPDATE usuario
        SET fecha_baja = NOW()
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Error al preparar la baja: " . $mysqli->error);
}

$stmt->bind_param("i", $id_usuario);

if (!$stmt->execute()) {
    $stmt->close();
    die("Error al darse de baja");
}

$stmt->close();

/* Cerrar sesión */
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header("Location: ../html/prototipo_login.php?baja=ok");
exit;
?>
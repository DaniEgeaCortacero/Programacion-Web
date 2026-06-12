<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_rol"]) || intval($_SESSION["id_rol"]) !== 1) {
    echo "<p>No tienes permiso para acceder a esta sección.</p>";
    exit;
}
?>
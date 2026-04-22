<?php
$mysqli = new mysqli("localhost", "practica", "practica", "practica");

if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");
?>
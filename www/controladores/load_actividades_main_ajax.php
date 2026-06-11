<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    exit;
}

require_once __DIR__ . "/load_actividades_main.php";

foreach ($actividades as $actividad) {
    include __DIR__ . "/../html/vistas/evento.php";
}
?>
<?php
session_start();

$_SESSION = [];

session_destroy();

// Impide la vuelta a la pagina si no estas logueado.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

header("Location: ../html/prototipo_login.php");
exit;
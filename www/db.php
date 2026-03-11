<?php
$host = "db";        // nombre del servicio en docker-compose
$user = "practica";
$pass = "practica";
$db   = "practica";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Error conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

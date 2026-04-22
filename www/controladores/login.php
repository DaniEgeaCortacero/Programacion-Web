<?php
session_start();
require_once "./db.php";

$mysqli = new mysqli('localhost', 'practica', 'practica', 'practica');

if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");

$login = $_POST['usuario'];   // puede ser usuario o correo
$clave = $_POST['clave'];

$sql = "SELECT id, usuario, correo, contrasena, id_rol
        FROM usuario
        WHERE (usuario = ? OR correo = ?)
        AND fecha_baja IS NULL
        LIMIT 1";

// Usamos prepare() y bind_param() para proteger la pagina frente
// inyecciones SQL.
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $login, $login);

$stmt->execute();
$resultado = $stmt->get_result();

// Si se obtiene un resultado:
if ($resultado->num_rows === 1) {
    $row = $resultado->fetch_assoc();

    if (password_verify($clave, $row['contrasena'])) {

        $_SESSION["autentica"] = "SIP";
        $_SESSION["usuarioactual"] = $row["usuario"];
        $_SESSION["id_usuario"] = $row["id"];
        $_SESSION["id_rol"] = $row["id_rol"];

        if ($row["id_rol"] == 1) {
            header("Location: ../html/prototipo_main.php");
        } else {
            header("Location: ../html/prototipo_main.php");
        }
        exit;

    } else {
        echo "<script>alert('Contraseña incorrecta'); window.location.href='../html/prototipo_login.php';</script>";
    }

} else {
    echo "<script>alert('Usuario o correo no existe'); window.location.href='../html/prototipo_login.php';</script>";
}

$stmt->close();
$mysqli->close();
?>
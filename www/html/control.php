<?
$mysql = new mysqli('localhost', 'practica', 'practica', 'practica');
$mysqli->set_charset("utf8");

$usuario = $mysqli->query("select usuario from usuario
                                 where usuario =  '" . htmlentities($_POST["usuario"]) . "'");

if ($usuario->num_rows > 0) {
    $sql = "select usuario
               from usuario
               where estado = 1
               and usuario = '" . htmlentities($_POST["usuario"]) . "' 
               and clave = '" . md5(htmlentities($_POST["clave"])) . "'";
    $clave = $mysqli->query($sql);

    if ($clave->num_rows > 0) {
        if ($row = $clave->fetch_assoc()) {
            session_start();
            $_SESSION["autentica"] = "SIP";
            $_SESSION["usuarioactual"] = $row["usuario"];
            //echo "usuario:".$row["usuario"]; 
            //nombre del usuario logueado.
            //Direccionamos a nuestra página principal del sistema.
            header("Location: app.php");
        }
    } else {
        echo "<script>alert('La contraseña del usuario no es correcta.');
               window.location.href=\"index.php\"</script>";
    }
} else {
    echo "<script>alert('El usuario no existe.');
    window.location.href=\"index.php\"</script>";
}

$usuario->free();
$clave->free();
$mysqli->close();



?>
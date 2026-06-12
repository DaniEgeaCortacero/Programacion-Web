<?php
require_once "./db.php";

if (
    empty($_POST["correo"]) ||
    empty($_POST["usuario"]) ||
    empty($_POST["clave"]) ||
    empty($_POST["clave2"]) ||
    empty($_POST["nombre"]) ||
    empty($_POST["apellidos"]) ||
    empty($_POST["fecha_nacimiento"])
) {
    echo "<script>
            alert('Debes rellenar todos los campos obligatorios.');
            window.location.href='../html/prototipo_login.php?modo=registro';
          </script>";
    exit;
}

$correo = trim($_POST["correo"]);
$usuario = trim($_POST["usuario"]);
$clave = $_POST["clave"];
$clave2 = $_POST["clave2"];
$nombre = trim($_POST["nombre"]);
$apellidos = trim($_POST["apellidos"]);
$fecha_nacimiento = $_POST["fecha_nacimiento"];
$id_tipo_actividad = $_POST["tipo_actividad"];
$id_pais = $_POST["pais"] ?? null;
$id_provincia = $_POST["provincia"] ?? null;
$id_localidad = $_POST["localidad"] ?? null;

if ($clave !== $clave2) {
    echo "<script>
            alert('Las contraseñas no coinciden.');
            window.location.href='../html/prototipo_login.php?modo=registro';
          </script>";
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            alert('El correo no tiene un formato válido.');
            window.location.href='../html/prototipo_login.php?modo=registro';
          </script>";
    exit;
}

// Comprobar si usuario o correo ya existen
$sql = "SELECT id 
        FROM usuario 
        WHERE usuario = ? OR correo = ?
        LIMIT 1";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $usuario, $correo);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo "<script>
            alert('El usuario o correo ya existe.');
            window.location.href='../html/prototipo_login.php?modo=registro';
          </script>";
    exit;
}

$stmt->close();

// Crear contraseña segura
$contrasena_hash = password_hash($clave, PASSWORD_DEFAULT);

// Rol usuario normal
$id_rol = 2;


$sql = "INSERT INTO usuario (
            usuario,
            correo,
            contrasena,
            nombre,
            apellidos,
            fecha_nacimiento,
            id_tipo_actividad_preferida,
            id_localidad,
            id_provincia,
            id_pais,
            id_rol,
            fecha_alta
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $mysqli->error);
}

$stmt->bind_param(
    "ssssssiiiii",
    $usuario,
    $correo,
    $contrasena_hash,
    $nombre,
    $apellidos,
    $fecha_nacimiento,
    $id_tipo_actividad,
    $id_localidad,
    $id_provincia,
    $id_pais,
    $id_rol
);


if ($stmt->execute()) {

    $id_usuario_nuevo = $stmt->insert_id;
    $stmt->close();

    /*
        Imagen de perfil por defecto
    */
    $nombre_imagen = "default.png";
    $tamano = 0;
    $alto = 512;
    $ancho = 512;
    $ruta = "../img/perfiles/default.png";
    $es_perfil = 1;

    $sql_img = "INSERT INTO imagen (
                    id_usuario,
                    nombre,
                    tamano,
                    alto,
                    ancho,
                    ruta,
                    es_perfil
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt_img = $mysqli->prepare($sql_img);

    if (!$stmt_img) {
        die("Error al preparar imagen por defecto: " . $mysqli->error);
    }

    $stmt_img->bind_param(
        "isiiisi",
        $id_usuario_nuevo,
        $nombre_imagen,
        $tamano,
        $alto,
        $ancho,
        $ruta,
        $es_perfil
    );

    $stmt_img->execute();
    $stmt_img->close();

    echo "<script>
            alert('Registro completado. Ya puedes iniciar sesión.');
            window.location.href='../html/prototipo_login.php?modo=login';
          </script>";

} else {
    echo "<script>
            alert('Error al registrar el usuario.');
            window.location.href='../html/prototipo_login.php?modo=registro';
          </script>";

    $stmt->close();
}

$mysqli->close();
?>
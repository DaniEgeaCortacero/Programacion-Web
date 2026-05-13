<?php
require_once __DIR__ . "/db.php";

$id_usuario = intval($_GET["id"] ?? 0);
$id_usuario_actual = $_SESSION["id_usuario"] ?? 0;

$sql = "SELECT 
            a.id,
            a.titulo,
            a.archivo_gpx,
            a.fecha_publicacion,
            ta.nombre AS tipo_actividad,
            u.usuario
        FROM actividad a
        JOIN usuario u ON a.id_usuario = u.id
        JOIN tipo_actividad ta ON a.id_tipo_actividad = ta.id
        WHERE a.id_usuario = ?
        ORDER BY a.fecha_publicacion DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();

$resultado = $stmt->get_result();

$actividades = [];

while ($actividad = $resultado->fetch_assoc()) {

    /* ################ IMAGENES ################ */

    $id_actividad = $actividad["id"];

    $sql_img = "SELECT 
                    i.id,
                    i.ruta,
                    i.nombre
                FROM actividad_imagen ai
                JOIN imagen i ON ai.id_imagen = i.id
                WHERE ai.id_actividad = ?";

    $stmt_img = $mysqli->prepare($sql_img);
    $stmt_img->bind_param("i", $id_actividad);
    $stmt_img->execute();

    $res_img = $stmt_img->get_result();

    $imagenes = [];

    while ($img = $res_img->fetch_assoc()) {
        $imagenes[] = $img;
    }

    $actividad["imagenes"] = $imagenes;
    $stmt_img->close();

    /* ################ COMPANEROS ################ */

    $sql_comp = "SELECT
                    u.id,
                    u.usuario,
                    ip.ruta AS imagen_perfil
                FROM actividad_companero ac
                JOIN usuario u ON u.id = ac.id_usuario
                LEFT JOIN imagen ip 
                    ON ip.id_usuario = u.id
                    AND ip.es_perfil = 1
                WHERE ac.id_actividad = ?";

    $stmt_comp = $mysqli->prepare($sql_comp);
    $stmt_comp->bind_param("i", $id_actividad);
    $stmt_comp->execute();

    $res_comp = $stmt_comp->get_result();

    $companeros = [];

    while ($comp = $res_comp->fetch_assoc()) {
        $companeros[] = $comp;
    }

    $actividad["companeros"] = $companeros;
    $stmt_comp->close();

    /* ################ APLAUSOS ################ */

    // Total aplausos
    $sql_aplausos = "
    SELECT COUNT(*) AS total
    FROM aplauso
    WHERE id_actividad = ?
    ";

    $stmt_aplausos = $mysqli->prepare($sql_aplausos);
    $stmt_aplausos->bind_param("i", $id_actividad);
    $stmt_aplausos->execute();

    $res_aplausos = $stmt_aplausos->get_result();
    $fila_aplausos = $res_aplausos->fetch_assoc();

    $actividad["n_aplausos"] = $fila_aplausos["total"];

    $stmt_aplausos->close();


    // Saber si el usuario actual ya dio aplauso
    $sql_mio = "
    SELECT id
    FROM aplauso
    WHERE id_actividad = ?
    AND id_usuario = ?
    LIMIT 1
    ";

    $stmt_mio = $mysqli->prepare($sql_mio);
    $stmt_mio->bind_param("ii", $id_actividad, $id_usuario_actual);
    $stmt_mio->execute();

    $res_mio = $stmt_mio->get_result();

    $actividad["mi_aplauso"] = ($res_mio->num_rows > 0);

    $stmt_mio->close(); 
    
    $actividades[] = $actividad;
}

$stmt->close();
?>
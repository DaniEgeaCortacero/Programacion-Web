<?php
require_once __DIR__ . "/../../controladores/admin_guard.php";
require_once __DIR__ . "/../../controladores/db.php";

function contar($mysqli, $tabla, $where = "1") {
    $sql = "SELECT COUNT(*) AS total FROM $tabla WHERE $where";
    $res = $mysqli->query($sql);
    $fila = $res->fetch_assoc();
    return intval($fila["total"]);
}

$total_usuarios = contar($mysqli, "usuario", "fecha_baja IS NULL");
$total_actividades = contar($mysqli, "actividad");
$total_imagenes = contar($mysqli, "imagen");
$total_localidades = contar($mysqli, "localidad");
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1>Panel de administración</h1>
        <p>Gestiona los datos auxiliares, los usuarios y las actividades de la plataforma.</p>
    </div>

    <div class="admin_grid">
        <article class="admin_card">
            <h2>Datos auxiliares</h2>
            <p>Administra tipos de actividad, países, provincias y localidades.</p>
            <a class="btn_admin" href="prototipo_main.php?vista=admin_datos&seccion=tipos">
                Ir a datos
            </a>
        </article>

        <article class="admin_card">
            <h2>Usuarios</h2>
            <p>Consulta, busca, edita y da de baja usuarios registrados o gestiona sus actividades e imágenes.</p>
            <a class="btn_admin" href="prototipo_main.php?vista=admin_usuarios">
                Ir a usuarios
            </a>
        </article>
    </div>

    <section class="admin_resumen">
        <h2>Resumen rápido</h2>

        <div class="admin_stats">
            <div class="stat_card">
                <span class="stat_num"><?= $total_usuarios ?></span>
                <span class="stat_label">Usuarios</span>
            </div>

            <div class="stat_card">
                <span class="stat_num"><?= $total_actividades ?></span>
                <span class="stat_label">Actividades</span>
            </div>

            <div class="stat_card">
                <span class="stat_num"><?= $total_imagenes ?></span>
                <span class="stat_label">Imágenes</span>
            </div>

            <div class="stat_card">
                <span class="stat_num"><?= $total_localidades ?></span>
                <span class="stat_label">Localidades</span>
            </div>
        </div>
    </section>

</section>
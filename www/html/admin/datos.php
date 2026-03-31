<?php
$seccion = $_GET['seccion'] ?? 'tipos';

$subvistas = [
    'tipos' => __DIR__ . '/datos/tipos.php',
    'paises' => __DIR__ . '/datos/paises.php',
    'provincias' => __DIR__ . '/datos/provincias.php',
    'localidades' => __DIR__ . '/datos/localidades.php'
];
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1>Gestión de datos auxiliares</h1>
        <p>Selecciona la categoría que quieres administrar.</p>
    </div>

    <nav class="admin_subnav">
        <a class="<?= $seccion === 'tipos' ? 'activo' : '' ?>"
           href="../html/prototipo_main.php?vista=admin_datos&seccion=tipos">
            Tipos de actividad
        </a>

        <a class="<?= $seccion === 'paises' ? 'activo' : '' ?>"
           href="../html/prototipo_main.php?vista=admin_datos&seccion=paises">
            Países
        </a>

        <a class="<?= $seccion === 'provincias' ? 'activo' : '' ?>"
           href="../html/prototipo_main.php?vista=admin_datos&seccion=provincias">
            Provincias
        </a>

        <a class="<?= $seccion === 'localidades' ? 'activo' : '' ?>"
           href="../html/prototipo_main.php?vista=admin_datos&seccion=localidades">
            Localidades
        </a>
    </nav>

    <div class="admin_contenido">
        <?php
        if (isset($subvistas[$seccion]) && file_exists($subvistas[$seccion])) {
            include $subvistas[$seccion];
        } else {
            echo "<p>La sección solicitada no existe.</p>";
        }
        ?>
    </div>

</section>
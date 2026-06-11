<?php
    session_start();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Expires: 0");

    if (!isset($_SESSION["autentica"]) || $_SESSION["autentica"] !== "SIP") {
        header("Location: ./prototipo_login.php");
        exit;
    }

    require_once "../controladores/update_ultima_conexion.php";
    require_once "../controladores/load_amistades.php";
    require_once "../controladores/load_solicitudes_amistad.php";
?>

<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Prototipo web</title>

    <link rel="stylesheet" href="../css/style_proto_main.css?v=15">
    <link rel="stylesheet" href="../css/style_proto_admin.css?v=8">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
</head>

<body>

    <?php include("../include/header.php"); ?>
    <!-- Menú lateral izquierdo -->
    <?php include("../include/sidebar.php"); ?>

    <main>
        <div class="mainLayout">

            <div class="zoneLeft">
                <div class="panel_amigos">

                    <div class="header_panel_amigos">
                        <h2>Amistades</h2>
                        <div class="search_bar">
                            <input type="text" id="busquedaAmigos" placeholder="Buscar usuario...">
                            <div id="resultados"></div>
                        </div>
                    </div>

                    <div class="lista_panel_amigos">
                        <?php include("./vistas/amistad.php"); ?>
                    </div>
                </div>
            </div>

            <div class="zoneMid">
                <?php
                $vista = $_GET['vista'] ?? 'home';

                $vistas = [
                    'home' => 'vistas/home.php',
                    'perfil' => 'vistas/perfil.php',
                    'usuario_detalles' => 'vistas/usuario_detalles.php',
                    'amistad_detalles' => 'vistas/amistad_detalles.php',
                    'crearEvento' => 'vistas/evento_creacion.php',
                    
                    'admin_administracion' => 'admin/administracion.php',
                    'admin_datos' => 'admin/datos.php',
                    'admin_usuarios' => 'admin/usuarios.php',
                    'admin_actividades' => 'admin/actividades.php'

                ];

                if(isset($vistas[$vista])){
                    include($vistas[$vista]);
                }
                ?>
            </div>
            
            <div class="zoneRight">
            </div>
        </div>
        
    </main>
    <footer>
        <p>Pié de página</p>
        <a href="https://es.textstudio.com/">Generador de fuentes</a>
    </footer>

<?php include("../include/modalGaleria.php"); ?>
<?php include("../include/modalEvento.php"); ?>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/@tmcw/togeojson@5.8.1/dist/togeojson.umd.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>

<script src="../js/app.js?v=20"></script>
<script src="../js/aplausos.js?v=1"></script>
<script src="../js/disable_cache.js?v=2"></script>
<script src="../js/busqueda_agregacion_usuarios.js?v=1"></script>
<script src="../js/load_actividades_main_ajax.js?v=2"></script>
</body>
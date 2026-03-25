<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prototipo web</title>
    <link rel="stylesheet" href="../css/style_proto_main.css?v=39">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/@tmcw/togeojson@5.8.1/dist/togeojson.umd.js"></script>
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
                        <? include("./vistas/amistad.php"); ?>
                        <? include("./vistas/amistad.php"); ?>
                        <? include("./vistas/amistad.php"); ?>
                    </div>
                </div>
            </div>

            <div class="zoneMid">
                <?php
                $vista = $_GET['vista'] ?? 'home';

                $vistas = [
                    'home' => 'vistas/home.php',
                    'perfil' => 'vistas/perfil.php',
                    'usuario' => 'vistas/amistad_detalles.php',
                    'crearEvento' => 'vistas/evento_creacion.php'
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
    </footer>

<script src="../js/app.js?v=13"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>
</body>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prototipo web</title>
    <link rel="stylesheet" href="../css/style_proto_main.css?v=30">
</head>
<body>

    <?php include("../include/header.php"); ?>
    <!-- Menú lateral izquierdo -->
    <?php include("../include/sidebar.php"); ?>

    <main>
        <div class="mainLayout">

            <div class="zoneLeft">
            </div>

            <div class="zoneMid">
                <?php
                $vista = $_GET['vista'] ?? 'home';

                $vistas = [
                    'home' => 'vistas/home.php',
                    'perfil' => 'vistas/perfil.php',
                    'usuarios' => 'vistas/usuarios.php'
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

<script src="../js/app.js?v=7"></script>
</body>
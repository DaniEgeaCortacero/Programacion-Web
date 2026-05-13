<?php
require_once "../controladores/load_actividades_main.php";
?>

<h1 class="titulo_home">Tablón de Actividades</h1>
<!--
<div class="evento">

    <div>
        <div class="map_card" id="map_card_1"></div>
    </div>

    <div class="evento_info">
        <p class="tipo_evento">Ciclismo</p>
        <h3 class="titulo_evento">Ruta por Ceuta</h3>

        <p class="evento_meta">👤 Creado por Dani</p>
        <p class="evento_meta">🖼 1 imágenes</p>
        <p class="evento_meta">👥 1 compañeros</p>
    </div>

    <div class="evento_imagenes">
        <img src="../img/paisaje3.jpg" alt="">
        <img src="../img/paisaje4.jpg" alt="">
        <div class="overlay_imagen">+2</div>
    </div>

    <div class="evento_lateral">
        <p class="aplausos">👏 33</p>
        <p class="usuarios_aplausos">Mucha gente</p>
        <button class="btn_evento ver" onclick="abrirEvento('../Prueba.gpx')">Ver más</button>
    </div>
</div>

<div class="evento">

    <div>
        <div class="map_card" id="map_card_2"></div>
    </div>

    <div class="evento_info">
        <p class="tipo_evento">Ciclismo</p>
        <h3 class="titulo_evento">Prueba</h3>

        <p class="evento_meta">👤 Creado por Dani</p>
        <p class="evento_meta">🖼 5 imágenes</p>
        <p class="evento_meta">👥 3 compañeros</p>
    </div>

    <div class="evento_imagenes">
        <img src="../img/paisaje1.jpg" alt="">
        <img src="../img/paisaje2.jpg" alt="">
        <div class="overlay_imagen">+2</div>
    </div>

    <div class="evento_lateral">
        <p class="aplausos">👏 0</p>
        <p class="usuarios_aplausos"></p>
        <button class="btn_evento ver" onclick="abrirEvento('../Prueba2.gpx')">Ver más</button>
    </div>
</div>
-->

<?php foreach ($actividades as $actividad): ?>
    <?php include("evento.php"); ?>
<?php endforeach; ?>

<?php  
include("../include/modal.php");
?>

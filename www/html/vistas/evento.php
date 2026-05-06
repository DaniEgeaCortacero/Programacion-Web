<?php
require_once "../controladores/load_imagenes_actividad.php";

$total_imagenes = count($actividad["imagenes"]);
$imagenes_visibles = array_slice($actividad["imagenes"], 0, 2);

$n_companeros = count($actividad["companeros"]);

$n_aplausos = intval($actividad["n_aplausos"]);
$mi_aplauso = boolval($actividad["mi_aplauso"]);
?>

<div class="evento">
    
    <div>
        <div class="map_card" id="map_card_1"></div>
    </div>

    <div class="evento_info">
        <p class="tipo_evento">
            <?= htmlspecialchars($actividades["tipo_actividad"]) ?>
        </p>

        <h3 class="titulo_evento">
            <?= htmlspecialchars($actividades["titulo"]) ?>
        </h3>

        <p class="evento_meta">
            👤 Publicado por 
            <?= htmlspecialchars($actividades["usuario"]) ?>
        </p>

        <p class="evento_meta">
            🖼 <?= $total_imagenes ?> imágenes
        </p>

        <p class="evento_meta">
            👥 <?= $n_companeros ?> compañeros
        </p>
        <p class="evento_meta">
            📅 <?= htmlspecialchars($actividades["fecha_publicacion"]) ?>
        </p>
    </div>

    <div class="evento_imagenes">
        <?php foreach ($imagenes_visibles as $imagen): ?>
        <img
            src="<?= htmlspecialchars($imagen["ruta"]) ?>"
            alt="Imagen actividad"
        >
        <?php endforeach; ?>

        <?php if($total_imagenes > 2): ?>
            <div class="overlay_imagen">
                +<?= $total_imagenes - 2 ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="evento_lateral">
        <p class="aplausos">
            👏 <?= $n_aplausos ?>
        </p>
        <button type="button" class="btn_evento ver" onclick="abrirEvento()">Ver más</button>
    </div>
</div>
<?php
require_once "../controladores/load_imagenes_actividad.php";
if (!isset($actividad) || !is_array($actividad)) {
    return;
}


$tipo_actividad = htmlspecialchars($actividad["tipo_actividad"] ?? "Sin tipo");
$titulo = htmlspecialchars($actividad["titulo"] ?? "Sin título");
$usuario = htmlspecialchars($actividad["usuario"] ?? "Usuario desconocido");
$fecha_publicacion = htmlspecialchars($actividad["fecha_publicacion"] ?? "");

$total_imagenes = count($actividad["imagenes"] ?? []);
$imagenes_visibles = array_slice($actividad["imagenes"] ?? [], 0, 2);

$n_companeros = count($actividad["companeros"] ?? []);
$n_aplausos = intval($actividad["n_aplausos"] ?? 0);
$mi_aplauso = boolval($actividad["mi_aplauso"] ?? false);
?>

<div class="evento">
    
    <div>
        <div 
            class="map_card mapa_gpx"
            id="map_card_<?= intval($actividad["id"]) ?>"
            data-gpx="<?= htmlspecialchars($actividad["archivo_gpx"]) ?>">
        </div>
    </div>

    <div class="evento_info">
        <p class="tipo_evento">
            <?= htmlspecialchars($tipo_actividad) ?>
        </p>

        <h3 class="titulo_evento">
            <?= htmlspecialchars($titulo) ?>
        </h3>

        <p class="evento_meta">
            👤 Publicado por 
            <?= htmlspecialchars($usuario) ?>
        </p>

        <p class="evento_meta">
            🖼 <?= $total_imagenes ?> imágenes
        </p>

        <p class="evento_meta">
            👥 <?= $n_companeros ?> compañeros
        </p>
        <p class="evento_meta">
            📅 <?= htmlspecialchars($fecha_publicacion) ?>
        </p>
    </div>

    <div class="evento_imagenes">
        <?php foreach ($actividad["imagenes"] as $index => $img): ?>
            <?php if ($index < 2): ?>
                <img
                    src="<?= htmlspecialchars($img["ruta"]) ?>"
                    class="evento_imagen"
                    alt="<?= htmlspecialchars($img["nombre"] ?? "Imagen actividad") ?>"
                    onclick='abrirGaleriaActividad(
                        <?= json_encode(array_column($actividad["imagenes"], "ruta")) ?>,
                        <?= $index ?>
                    )'
                >
            <?php elseif ($index === 2): ?>
                <button
                    type="button"
                    class="evento_imagen_extra"
                    onclick='abrirGaleriaActividad(
                        <?= json_encode(array_column($actividad["imagenes"], "ruta")) ?>,
                        <?= $index ?>
                    )'>
                    +<?= count($actividad["imagenes"]) - 2 ?>
                </button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="evento_lateral">
        <button 
            type="button"
            class="btn_aplauso <?= !empty($actividad["mi_aplauso"]) ? 'activo' : '' ?>"
            data-id-actividad="<?= intval($actividad["id"]) ?>"
            onclick="toggleAplauso(this)">
            👏 <span><?= intval($actividad["n_aplausos"]) ?></span>
        </button>


        <?php
            $imagenes_modal = array_map(function($img) {
                return [
                    "ruta" => $img["ruta"],
                    "nombre" => $img["nombre"] ?? ""
                ];
            }, $actividad["imagenes"] ?? []);

            $companeros_modal = array_map(function($c) {
                return [
                    "usuario" => $c["usuario"],
                    "imagen_perfil" => $c["imagen_perfil"] ?? ""
                ];
            }, $actividad["companeros"] ?? []);
        ?>

        <button 
            type="button"
            class="btn_ver_mas"
            onclick="abrirEvento(<?= intval($actividad['id']) ?>)">
            Ver más
        </button>
    </div>
</div>
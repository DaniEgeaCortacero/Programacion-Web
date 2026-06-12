<?php
$modo_edicion = ($_GET["vista"] ?? "") === "editarEvento";
$evento_edicion = null;
$companeros_edicion = [];

if ($modo_edicion) {
    require_once "../controladores/load_actividad_edicion.php";
}

if ($modo_edicion && !$evento_edicion) {
    echo "<p>No se ha encontrado la actividad o no tienes permiso para editarla.</p>";
    return;
}

$action_form = $modo_edicion
    ? "../controladores/actualizar_evento.php"
    : "../controladores/crear_evento.php";

$titulo_pagina = $modo_edicion ? "Editar evento" : "Crear evento";
$subtitulo_pagina = $modo_edicion
    ? "Modifica los datos de tu actividad"
    : "Organiza una nueva actividad para tus amigos";

$texto_boton = $modo_edicion ? "Guardar cambios" : "Crear evento";

$fecha_valor = "";
$hora_valor = "";

if (!empty($evento_edicion["fecha_evento"])) {
    $fecha_valor = date("Y-m-d", strtotime($evento_edicion["fecha_evento"]));
    $hora_valor = date("H:i", strtotime($evento_edicion["fecha_evento"]));
}

require_once "../controladores/load_tipos_actividad.php";
require_once "../controladores/load_paises.php";
require_once "../controladores/load_amistades.php";
?>

<div class="crear_evento_card">

    <div class="crear_evento_header">
        <div class="crear_evento_header_info">
            <div class="crear_evento_icono"><?= $modo_edicion ? "✏️" : "📅" ?></div>

            <div>
                <h1><?= htmlspecialchars($titulo_pagina) ?></h1>
                <p><?= htmlspecialchars($subtitulo_pagina) ?></p>
            </div>
        </div>
    </div>

    <div class="crear_evento_body">
        <form 
            id="form_crear_evento" 
            class="form_crear_evento" 
            method="POST" 
            action="<?= htmlspecialchars($action_form) ?>"
            enctype="multipart/form-data"
        >

            <?php if ($modo_edicion): ?>
                <input 
                    type="hidden" 
                    name="id_actividad" 
                    value="<?= intval($evento_edicion["id"]) ?>"
                >
            <?php endif; ?>

            <section class="seccion_evento">
                <h2>Información general</h2>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="titulo">Título</label>
                        <input 
                            type="text" 
                            id="titulo" 
                            name="titulo" 
                            value="<?= htmlspecialchars($evento_edicion["titulo"] ?? "") ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="tipo">Tipo de actividad</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecciona una actividad</option>

                            <?php foreach ($tipos_actividad as $tipo): ?>
                                <option 
                                    value="<?= intval($tipo["id"]) ?>"
                                    <?= (($evento_edicion["id_tipo_actividad"] ?? null) == $tipo["id"]) ? "selected" : "" ?>
                                >
                                    <?= htmlspecialchars($tipo["nombre"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="campo">
                    <label for="descripcion">Descripción</label>
                    <textarea 
                        id="descripcion" 
                        name="descripcion" 
                        rows="4"
                    ><?= htmlspecialchars($evento_edicion["descripcion"] ?? "") ?></textarea>
                </div>
            </section>

            <section class="seccion_evento">
                <h2>Fecha y ubicación</h2>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="fecha">Fecha</label>
                        <input 
                            type="date" 
                            id="fecha" 
                            name="fecha" 
                            value="<?= htmlspecialchars($fecha_valor) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="hora">Hora</label>
                        <input 
                            type="time" 
                            id="hora" 
                            name="hora"
                            value="<?= htmlspecialchars($hora_valor) ?>"
                        >
                    </div>
                </div>

                <div class="campo">
                    <label>País</label>
                    <select name="pais" id="pais_evento" required>
                        <option value="">Selecciona país</option>

                        <?php foreach ($paises as $pais): ?>
                            <option 
                                value="<?= intval($pais["id"]) ?>" 
                                data-iso="<?= htmlspecialchars($pais["iso"]) ?>"
                                <?= (($evento_edicion["id_pais"] ?? null) == $pais["id"]) ? "selected" : "" ?>
                            >
                                <?= htmlspecialchars($pais["nombre"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid_doble">
                    <div class="campo" id="campo_provincia_evento">
                        <label>Provincia</label>

                        <select name="provincia" id="provincia_evento" required>
                            <option value="">Selecciona provincia</option>
                        </select>
                    </div>

                    <div class="campo" id="campo_localidad_evento">
                        <label>Localidad</label>

                        <select name="localidad" id="localidad_evento" required>
                            <option value="">Selecciona localidad</option>
                        </select>
                    </div>
                </div>

                <div class="campo">
                    <label for="ruta">Ruta / mapa</label>

                    <?php if ($modo_edicion && !empty($evento_edicion["archivo_gpx"])): ?>
                        <p class="texto_gpx_actual">
                            GPX actual: <?= htmlspecialchars(basename($evento_edicion["archivo_gpx"])) ?>
                        </p>
                    <?php endif; ?>

                    <input 
                        class="input_gpx" 
                        type="file" 
                        id="ruta" 
                        name="gpx_ruta" 
                        accept=".gpx"
                        <?= $modo_edicion ? "" : "required" ?>
                    >
                </div>
            </section>

            <section class="seccion_evento">
                <h2>Detalles adicionales</h2>

                <div class="campo">
                    <label for="imagenes">Imágenes</label>

                    <?php if ($modo_edicion): ?>
                        <p class="texto_imagenes_actuales">
                            Si subes nuevas imágenes, se añadirán a la actividad.
                        </p>
                    <?php endif; ?>

                    <input 
                        type="file" 
                        id="imagenes" 
                        name="imagenes[]" 
                        accept="image/*" 
                        multiple
                    >
                </div>

                <div class="campo">
                    <label>Compañeros de actividad</label>

                    <div class="lista_companeros_creacion">

                        <?php if (empty($amistades)): ?>

                            <p>No tienes amistades añadidas.</p>

                        <?php else: ?>

                            <?php foreach ($amistades as $amigo): ?>
                                <?php
                                    $id_amigo = intval($amigo["id"]);
                                    $checked = in_array($id_amigo, $companeros_edicion) ? "checked" : "";

                                    $imagen_amigo = !empty($amigo["imagen"])
                                        ? $amigo["imagen"]
                                        : (!empty($amigo["imagen_perfil"]) ? $amigo["imagen_perfil"] : "../img/default.png");
                                ?>

                                <label class="item_companero_creacion">

                                    <input 
                                        type="checkbox" 
                                        name="companeros[]" 
                                        value="<?= $id_amigo ?>"
                                        <?= $checked ?>
                                    >

                                    <img 
                                        src="<?= htmlspecialchars($imagen_amigo) ?>"
                                        alt=""
                                    >

                                    <span>
                                        <?= htmlspecialchars($amigo["usuario"]) ?>
                                    </span>

                                </label>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </div>
                </div>
            </section>

            <div class="acciones_formulario">
                <button type="submit" class="btn_publicar_evento abajo">
                    <?= htmlspecialchars($texto_boton) ?>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
window.EVENTO_EDICION = {
    modo: <?= $modo_edicion ? "true" : "false" ?>,
    idPais: <?= intval($evento_edicion["id_pais"] ?? 0) ?>,
    idProvincia: <?= intval($evento_edicion["id_provincia"] ?? 0) ?>,
    idLocalidad: <?= intval($evento_edicion["id_localidad"] ?? 0) ?>
};
</script>
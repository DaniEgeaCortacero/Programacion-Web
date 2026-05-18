<?php
require_once "../controladores/load_tipos_actividad.php";
require_once "../controladores/load_paises.php";
require_once "../controladores/load_amistades.php";
?>

<div class="crear_evento_card">

    <div class="crear_evento_header">
        <div class="crear_evento_header_info">
            <div class="crear_evento_icono">📅</div>

            <div>
                <h1>Crear evento</h1>
                <p>Organiza una nueva actividad para tus amigos</p>
            </div>
        </div>

    </div>

    <div class="crear_evento_body">
        <form id="form_crear_evento" class="form_crear_evento" method="POST" action="../controladores/crear_evento.php"
            enctype="multipart/form-data">

            <section class="seccion_evento">
                <h2>Información general</h2>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="titulo">Título</label>
                        <input type="text" id="titulo" name="titulo" required>
                    </div>

                    <div class="campo">
                        <label for="tipo">Tipo de actividad</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecciona una actividad</option>
                            <?php foreach ($tipos_actividad as $tipo): ?>
                                <option value="<?= $tipo["id"] ?>">
                                    <?= htmlspecialchars($tipo["nombre"]) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="campo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4"></textarea>
                </div>
            </section>

            <section class="seccion_evento">
                <h2>Fecha y ubicación</h2>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha" required>
                    </div>

                    <div class="campo">
                        <label for="hora">Hora</label>
                        <input type="time" id="hora" name="hora">
                    </div>
                </div>

                <div class="campo">
                    <label>País</label>
                    <select name="pais" id="pais_evento" required>
                        <option value="">Selecciona país</option>

                        <?php foreach ($paises as $pais): ?>
                            <option value="<?= $pais["id"] ?>" data-iso="<?= $pais["iso"] ?>">
                                <?= htmlspecialchars($pais["nombre"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid_doble">
                    <div class="campo" id="campo_provincia_evento">
                        <label>Provincia</label>
                        <input type="text" name="provincia" required>
                    </div>

                    <div class="campo" id="campo_localidad_evento">
                        <label>Localidad</label>
                        <input type="text" name="localidad" required>
                    </div>
                </div>

                <div class="campo">
                    <label for="ruta">Ruta / mapa</label>
                    <input class="input_gpx" type="file" id="ruta" name="gpx_ruta" accept=".gpx">
                </div>
            </section>

            <section class="seccion_evento">
                <h2>Detalles adicionales</h2>

                <div class="campo">
                    <label for="imagenes">Imágenes</label>
                    <input type="file" id="imagenes" name="imagenes[]" accept="image/*" multiple>
                </div>

                <div class="campo">
                    <label>Compañeros de actividad</label>

                    <div class="lista_companeros_creacion">

                        <?php if (empty($amistades)): ?>

                            <p>No tienes amistades añadidas.</p>

                        <?php else: ?>

                            <?php foreach ($amistades as $amigo): ?>

                                <label class="item_companero_creacion">

                                    <input type="checkbox" name="companeros[]" value="<?= $amigo["id"] ?>"
                                    >

                                    <img src="<?= !empty($amigo["imagen_perfil"])
                                        ? htmlspecialchars($amigo["imagen_perfil"])
                                        : '../img/default.png' ?>"
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
                <button type="submit" class="btn_publicar_evento abajo">Crear evento</button>
            </div>

        </form>
    </div>
</div>
<div class="crear_evento_card">

    <div class="crear_evento_header">
        <div class="crear_evento_header_info">
            <div class="crear_evento_icono">📅</div>

            <div>
                <h1>Crear evento</h1>
                <p>Organiza una nueva actividad para tus amigos</p>
            </div>
        </div>

        <button class="btn_publicar_evento">Publicar evento</button>
    </div>

    <div class="crear_evento_body">
        <form class="form_crear_evento" method="POST" action="" enctype="multipart/form-data">

            <section class="seccion_evento">
                <h2>Información general</h2>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="titulo">Título</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Título del evento">
                    </div>

                    <div class="campo">
                        <label for="tipo">Tipo de actividad</label>
                        <select id="tipo" name="tipo">
                            <option value="">Selecciona una actividad</option>
                            <option value="senderismo">Senderismo</option>
                            <option value="ciclismo">Ciclismo</option>
                            <option value="running">Running</option>
                            <option value="gym">Gimnasio</option>
                        </select>
                    </div>
                </div>

                <div class="campo">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" placeholder="Describe la actividad"></textarea>
                </div>
            </section>

            <section class="seccion_evento">
                <h2>Fecha y ubicación</h2>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="fecha">Fecha</label>
                        <input type="date" id="fecha" name="fecha">
                    </div>

                    <div class="campo">
                        <label for="hora">Hora</label>
                        <input type="time" id="hora" name="hora">
                    </div>
                </div>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="localidad">Localidad</label>
                        <input type="text" id="localidad" name="localidad" placeholder="Localidad">
                    </div>

                    <div class="campo">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" id="ciudad" name="ciudad" placeholder="Ciudad">
                    </div>
                </div>

                <div class="campo">
                    <label for="ruta">Ruta / mapa</label>
                    <input class="input_gpx" type="file" id="ruta" name="gpx_ruta" accept=".gpx">
                </div>

                <div class="preview_ruta" id="preview_ruta">
                    <h3>Vista previa de la ruta</h3>
                    <div id="mapa_preview"></div>
                    <p id="mensaje_preview">Selecciona un archivo GPX para ver la ruta en el mapa.</p>
                </div>

            </section>

            <section class="seccion_evento">
                <h2>Detalles adicionales</h2>

                <div class="grid_doble">
                    <div class="campo">
                        <label for="aforo">Número máximo de personas</label>
                        <input type="number" id="aforo" name="aforo" placeholder="Ej: 10">
                    </div>

                    <div class="campo">
                        <label for="imagenes">Imágenes</label>
                        <input type="file" id="imagenes" name="imagenes[]" multiple>
                    </div>
                </div>
            </section>

            <div class="acciones_formulario">
                <button type="submit" class="btn_publicar_evento abajo">Crear evento</button>
            </div>

        </form>
    </div>
</div>
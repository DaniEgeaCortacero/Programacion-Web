<div class="modal_evento" id="modal_evento">
    <div class="modal_card">

        <button class="cerrar_modal" id="cerrar_modal">×</button>

        <div class="header_evento">
            <h3 id="modal_tipo">Tipo</h3>
            <h2 id="modal_titulo">Título</h2>
        </div>

        <div class="modal_evento_scroll">

            <section class="modal_seccion">
                <h4>Ruta</h4>
                <div id="mapa_modal"></div>
            </section>

            <section class="modal_seccion">
                <h4>Datos y participantes</h4>

                <div id="modal_publicador" class="modal_publicador">
                    Publicado por...
                </div>

                <div id="modal_fecha" class="modal_fecha">
                    Fecha...
                </div>

                <div id="modal_fecha_evento" class="modal_fecha">
                    Fecha del evento...
                </div>

                <div id="modal_descripcion" class="modal_descripcion">
                    Descripción...
                </div>

                
                <h4 class="titulo_companeros_modal">Compañeros de actividad</h4>

                <div id="modal_companeros" class="modal_companeros">
                    Compañeros
                </div>
            </section>

            <section class="modal_seccion">
                <h4>Fotos</h4>
                <div class="imagenes_evento_detalles" id="modal_imagenes">
                    Imágenes
                </div>
            </section>

        </div>

        <div class="footer_evento_detalles" id="modal_footer">
            <div class="evento_aplausos">
                👏 aplausos
            </div>

            <div class="botones_evento">
                <button class="btn_evento editar" onclick="editarEvento()">Editar</button>
            </div>
        </div>

    </div>
</div>
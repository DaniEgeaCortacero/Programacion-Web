<div class="modal_evento" id="modal_evento">
        <div class="modal_card">
            <button class="cerrar_modal" id="cerrar_modal">✖</button>

            <div class="header_evento">
                <h3 id="modal_tipo">Tipo</h3>
                <h2 id="modal_titulo">Titulo</h2>
            </div>

            <div class="contenido_evento_detalles">
                <div id="mapa_modal"></div>

                <div class="imagenes_evento_detalles" id="modal_imagenes">
                    Imágenes
                </div>

                <div class="companeros_evento_detalles" id="modal_companeros">
                    Compañeros
                </div>
            </div>

            <div class="footer_evento_detalles" id="modal_footer">
                <div class="evento_aplausos">
                👏 aplausos
                </div>
                <div class="botones_evento">
                    <button class="btn_evento editar" onclick="editarEvento()">Editar</button>
                    <button class="btn_evento participar" onclick="participarEvento()">Participar</button>
                </div>
            </div>
        </div>
</div>
<div id="modal_galeria" class="modal_galeria">
    <div class="modal_galeria_contenido">

        <button type="button" class="modal_galeria_cerrar" onclick="cerrarGaleriaActividad()">
            ×
        </button>

        <button type="button" class="modal_galeria_nav modal_galeria_prev" onclick="moverGaleriaActividad(-1)">
            ‹
        </button>

        <img id="modal_galeria_img" src="" alt="Imagen ampliada">

        <button type="button" class="modal_galeria_nav modal_galeria_next" onclick="moverGaleriaActividad(1)">
            ›
        </button>

        <div id="modal_galeria_contador" class="modal_galeria_contador"></div>

    </div>
</div>
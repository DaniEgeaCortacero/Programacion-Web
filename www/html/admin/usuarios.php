<section class="admin_panel">

    <div class="admin_header">
        <h1>Gestión de usuarios</h1>
        <p>Consulta, busca y gestiona los usuarios registrados.</p>
    </div>

    <!-- Buscador -->
    <div class="admin_busqueda">
        <input type="text" placeholder="Buscar usuario..." id="buscadorUsuarios">
    </div>

    <!-- Tabla de usuarios -->
    <div class="admin_contenido">

        <table class="admin_tabla">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                <!-- Ejemplo: -->
                <tr>
                    <td>Juan Pérez</td>
                    <td>juan@email.com</td>
                    <td>Activo</td>
                    <td class="acciones">

                        <button type="button" class="btn_tabla" onclick="abrirModalImagenes(1)">Imágenes</button>
                        <a class="btn_tabla" href="../html/prototipo_main.php?vista=admin_actividades&usuario=1">Actividades</a>
                        <button type="button" class="btn_tabla">Editar</button>
                        <button type="button" class="btn_tabla btn_peligro">Baja</button>

                    </td>
                </tr>

            </tbody>
        </table>

    </div>

</section>

<div id="modalImagenes" class="modal">
    <div class="modal_contenido">
        <span class="cerrar" onclick="cerrarModal()">×</span>

        <h2>Imágenes del usuario</h2>

        <div class="grid_imagenes" id="contenidoImagenes">
            <!-- Se carga dinámicamente -->
        </div>
    </div>
</div>
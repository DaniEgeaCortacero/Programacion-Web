<section class="perfil">
    <div class="perfil_card">

        <div class="header_perfil">
            <div class="header_perfil_izq">
                <img src="../img/user_icon_green.png" alt="Foto de perfil" class="imagen_perfil">

                <div class="headerDatosIniciales">
                    <h1>Nombre Apellidos</h1>
                    <p>@nombre_usuario</p>
                </div>
            </div>

            <div class="header_perfil_der">
                <button type="button" id="editPerfilBtn" class="btn_primario">Editar perfil</button>
            </div>
        </div>

        <form id="perfil_form" class="perfil_contenido">

            <div class="seccion_datos">
                <h2>Información personal</h2>

                <div class="datos_grid">
                    <div class="datos_item">
                        <label>Usuario</label>
                        <span class="modo_vista">Nombre_usuario</span>
                        <input class="modo_edicion" type="text" value="Nombre_usuario">
                    </div>

                    <div class="datos_item">
                        <label>Nombre</label>
                        <span class="modo_vista">Nombre</span>
                        <input class="modo_edicion" type="text" value="Nombre">
                    </div>

                    <div class="datos_item">
                        <label>Apellidos</label>
                        <span class="modo_vista">Apellidos</span>
                        <input class="modo_edicion" type="text" value="Apellidos">
                    </div>

                    <div class="datos_item">
                        <label>Fecha de nacimiento</label>
                        <span class="modo_vista">dd/mm/aa</span>
                        <input class="modo_edicion" type="date" value="2000-00-00">
                    </div>

                    <div class="datos_item">
                        <label>Actividad preferida</label>
                        <span class="modo_vista">Actividad</span>
                        <input class="modo_edicion" type="text" value="Actividad">
                    </div>
                </div>
            </div>

            <div class="seccion_datos">
                <h2>Ubicación</h2>

                <div class="datos_grid">
                    <div class="datos_item">
                        <label>País</label>
                        <span class="modo_vista">País</span>
                        <input class="modo_edicion" type="text" value="País">
                    </div>

                    <div class="datos_item">
                        <label>Localidad</label>
                        <span class="modo_vista">Localidad</span>
                        <input class="modo_edicion" type="text" value="Localidad">
                    </div>

                    <div class="datos_item">
                        <label>Ciudad</label>
                        <span class="modo_vista">Ciudad</span>
                        <input class="modo_edicion" type="text" value="Ciudad">
                    </div>
                </div>
            </div>

            <div class="seccion_datos">
                <h2>Cuenta</h2>

                <div class="datos_grid">
                    <div class="datos_item datos_item_full">
                        <label>Correo electrónico</label>
                        <span class="modo_vista">email@gmail.com</span>
                        <input class="modo_edicion" type="email" value="email@gmail.com">
                    </div>
                </div>
            </div>

            <div class="panel_perfil">
                <button type="button" id="cancelEditBtn" class="btn_secundario">Cancelar</button>
                <button type="submit" class="btn_primario">Guardar cambios</button>
            </div>

        </form>
    </div>
</section>
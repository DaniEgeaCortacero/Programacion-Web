<?php 
require_once "../controladores/load_actividades_usuario.php";
?>

<section class="perfil">
    <div class="perfil_card">

        <div class="header_usuario">
            <div class="header_perfil_izq">
                <img src="../img/user_icon_blue.png" alt="Foto de perfil" class="imagen_usuario">

                <div class="headerDatosIniciales">
                    <h1>Nombre Apellidos</h1>
                    <p>@nombre_usuario</p>
                </div>
            </div>
        </div>

        <form id="perfil_form" class="perfil_contenido">

            <div class="seccion_datos">
                <h2>Información personal</h2>

                <div class="datos_grid">

                    <div class="datos_item">
                        <label>Nombre</label>
                        <span class="modo_vista">Nombre</span>
                        <input class="modo_edicion" type="text" value="Nombre">
                    </div>

                    <div class="datos_item">
                        <label>Apellidos</label>
                        <span class="modo_vista">Apellidos</span>
                    </div>

                    <div class="datos_item">
                        <label>Actividad preferida</label>
                        <span class="modo_vista">Actividad</span>
                    </div>
                </div>
            </div>

            <div class="seccion_datos">
                <h2>Actividades publicadas</h2>

                <div class="datos_actividad">
                    <?php foreach ($actividades as $actividad): ?>
                        <?php include("evento.php"); ?>
                    <?php endforeach; ?>
                </div>
            </div>



        </form>
    </div>

</section>


<?php  
    include("../include/modal.php");
?>
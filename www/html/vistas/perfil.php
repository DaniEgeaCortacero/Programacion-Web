<?php 
require_once "../controladores/ver_perfil.php"; 
require_once "../controladores/load_tipos_actividad.php";
?>

<section class="perfil">
    <div class="perfil_card">

        <form id="form_imagen_perfil" method="POST" action="../controladores/imagen_perfil.php" enctype="multipart/form-data">
            <input 
                type="file" 
                id="input_imagen_perfil" 
                name="imagen_perfil" 
                accept="image/*" 
                hidden
            >
        </form>

            <div class="header_perfil">
                <div class="header_perfil_izq">
                    

                    <img 
                        src="<?= !empty($perfil["imagen_perfil"]) ? htmlspecialchars($perfil["imagen_perfil"]) : '../img/default.png' ?>"  
                        alt="Foto de perfil" 
                        class="imagen_perfil"
                        onclick="document.getElementById('input_imagen_perfil').click();"
                    >

                    <div class="headerDatosIniciales">
                        <h1><?= htmlspecialchars($perfil["nombre"] . " " . $perfil["apellidos"]) ?></h1>
                        <p>@<?= htmlspecialchars($perfil["usuario"]) ?></p>
                    </div>
                </div>

                <div class="header_perfil_der">
                    <button type="button" id="editPerfilBtn" class="btn_primario">Editar perfil</button>
                </div>
            </div>
        <form id="perfil_form" class="perfil_contenido" method="POST" action="../controladores/actualizar_perfil.php">

            <div class="perfil_inner">

                <div class="seccion_datos">
                    <h2>Información personal</h2>

                    <div class="datos_grid">
                        <div class="datos_item">
                            <label>Usuario</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["usuario"]) ?></span>
                            <input class="modo_edicion" type="text" name="usuario" value="<?= htmlspecialchars($perfil["usuario"]) ?>">
                        </div>

                        <div class="datos_item">
                            <label>Nombre</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["nombre"]) ?></span>
                            <input class="modo_edicion" type="text" name="nombre" value="<?= htmlspecialchars($perfil["nombre"]) ?>">
                        </div>

                        <div class="datos_item">
                            <label>Apellidos</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["apellidos"]) ?></span>
                            <input class="modo_edicion" type="text" name="apellidos" value="<?= htmlspecialchars($perfil["apellidos"]) ?>">
                        </div>

                        <div class="datos_item">
                            <label>Fecha de nacimiento</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["fecha_nacimiento"]) ?></span>
                            <input class="modo_edicion" type="date" name="fecha_nacimiento" value="<?= htmlspecialchars($perfil["fecha_nacimiento"]) ?>">
                        </div>

                        <div class="datos_item">
                            <label>Actividad preferida</label>

                            <span class="modo_vista">
                                <?= htmlspecialchars($perfil["tipo_actividad"] ?? "Sin actividad") ?>
                            </span>

                            <select class="modo_edicion" name="tipo_actividad">
                                <?php foreach ($tipos_actividad as $tipo): ?>
                                    <option 
                                        value="<?= $tipo["id"] ?>"
                                        <?= ($tipo["nombre"] == $perfil["tipo_actividad"]) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($tipo["nombre"]) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="seccion_datos">
                    <h2>Ubicación</h2>

                    <div class="datos_grid">
                        <div class="datos_item">
                            <label>País</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["pais"] ?? "Sin país") ?></span>
                            <input class="modo_edicion" type="text" value="<?= htmlspecialchars($perfil["pais"] ?? "") ?>">
                        </div>

                        <div class="datos_item">
                            <label>Provincia</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["provincia"] ?? "Sin provincia") ?></span>
                            <input class="modo_edicion" type="text" value="<?= htmlspecialchars($perfil["provincia"] ?? "") ?>">
                        </div>

                        <div class="datos_item">
                            <label>Localidad</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["localidad"] ?? "Sin localidad") ?></span>
                            <input class="modo_edicion" type="text" value="<?= htmlspecialchars($perfil["localidad"] ?? "") ?>">
                        </div>
                    </div>
                </div>

                <div class="seccion_datos">
                    <h2>Cuenta</h2>

                    <div class="datos_grid">
                        <div class="datos_item datos_item_full">
                            <label>Correo electrónico</label>
                            <span class="modo_vista_email"><?= htmlspecialchars($perfil["correo"]) ?></span>
                        </div>
                    </div>
                </div>

                <div class="panel_perfil">
                    <button type="button" id="cancelEditBtn" class="btn_secundario">Cancelar</button>
                    <button type="submit" class="btn_primario">Guardar cambios</button>
                </div>

                <div class="seccion_datos mis_actividades">
                    <h2>Mis actividades</h2>

                    <div class="datos_actividad">
                        <?php include("evento.php"); ?>
                        <?php include("evento.php"); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
document.getElementById("input_imagen_perfil").addEventListener("change", function () {
    const archivo = this.files[0];

    if (archivo) {
        // Preview
        document.querySelector(".imagen_perfil").src = URL.createObjectURL(archivo);

        // Enviar automáticamente el formulario
        document.getElementById("form_imagen_perfil").submit();
    }
});
</script>
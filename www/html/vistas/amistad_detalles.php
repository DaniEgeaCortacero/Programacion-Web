<?php
require_once "../controladores/ver_perfil.php";
?>

<?php if (!$perfil): ?>

<section class="perfil">
    <div class="perfil_card">
        <h2>Usuario no encontrado</h2>
    </div>
</section>

<?php elseif (!$es_amigo && !$es_mi_perfil): ?>

<section class="perfil">
    <div class="perfil_card">
        <h2>No puedes ver este perfil completo</h2>
        <p>Solo puedes acceder al perfil completo de tus amistades.</p>

        <a 
            href="index.php?vista=usuario_detalles&id=<?= intval($perfil["id"]) ?>" 
            class="btn_secundario">
            Ver perfil público
        </a>
    </div>
</section>

<?php else: ?>

<?php
$solo_ultima = false;
require_once "../controladores/load_actividades_usuario.php";
?>

<section class="perfil">
    <div class="perfil_card">

        <div class="header_perfil">
            <div class="header_perfil_izq">
                <img 
                    src="<?= !empty($perfil["imagen_perfil"]) ? htmlspecialchars($perfil["imagen_perfil"]) : '../img/default.png' ?>" 
                    alt="Foto de perfil" 
                    class="imagen_perfil"
                >

                <div class="headerDatosIniciales">
                    <h1><?= htmlspecialchars($perfil["nombre"] . " " . $perfil["apellidos"]) ?></h1>
                    <p>@<?= htmlspecialchars($perfil["usuario"]) ?></p>
                </div>
            </div>
        </div>

        <div class="perfil_contenido">
            <div class="perfil_inner">

                <div class="seccion_datos">
                    <h2>Información personal</h2>

                    <div class="datos_grid">
                        <div class="datos_item">
                            <label>Usuario</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["usuario"]) ?></span>
                        </div>

                        <div class="datos_item">
                            <label>Nombre</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["nombre"]) ?></span>
                        </div>

                        <div class="datos_item">
                            <label>Apellidos</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["apellidos"]) ?></span>
                        </div>

                        <div class="datos_item">
                            <label>Fecha de nacimiento</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["fecha_nacimiento"]) ?></span>
                        </div>

                        <div class="datos_item">
                            <label>Actividad preferida</label>
                            <span class="modo_vista">
                                <?= htmlspecialchars($perfil["tipo_actividad"] ?? "Sin actividad") ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="seccion_datos">
                    <h2>Ubicación</h2>

                    <div class="datos_grid">
                        <div class="datos_item">
                            <label>País</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["pais"] ?? "Sin país") ?></span>
                        </div>

                        <div class="datos_item">
                            <label>Provincia</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["provincia"] ?? "Sin provincia") ?></span>
                        </div>

                        <div class="datos_item">
                            <label>Localidad</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["localidad"] ?? "Sin localidad") ?></span>
                        </div>
                    </div>
                </div>

                <div class="seccion_datos mis_actividades">
                    <h2>Actividades publicadas</h2>

                    <div class="datos_actividad">
                        <?php if (!empty($actividades)): ?>
                            <?php foreach ($actividades as $actividad): ?>
                                <?php include("evento.php"); ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Este usuario todavía no ha publicado actividades.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<?php endif; ?>
<?php
require_once "../controladores/ver_perfil.php";

$solo_ultima = true;
require_once "../controladores/load_actividades_usuario.php";
?>

<?php if (!$perfil): ?>

<section class="perfil">
    <div class="perfil_card">
        <h2>Usuario no encontrado</h2>
    </div>
</section>

<?php else: ?>

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

            <div class="header_perfil_der">

                <?php if ($estado_relacion === "ninguna"): ?>
                    <button 
                        type="button" 
                        class="btn_primario"
                        onclick="agregarAmigo(<?= intval($perfil["id"]) ?>)">
                        Agregar
                    </button>

                <?php elseif ($estado_relacion === "pendiente_enviada"): ?>
                    <button type="button" class="btn_secundario" disabled>
                        Solicitud enviada
                    </button>

                <?php elseif ($estado_relacion === "pendiente_recibida"): ?>
                    <button 
                        type="button" 
                        class="btn_primario"
                        onclick="agregarAmigo(<?= intval($perfil["id"]) ?>)">
                        Aceptar amistad
                    </button>

                <?php elseif ($estado_relacion === "aceptada"): ?>
                    <a 
                        href="index.php?vista=amistad_detalles&id=<?= intval($perfil["id"]) ?>" 
                        class="btn_primario">
                        Ver perfil completo
                    </a>
                <?php endif; ?>

            </div>
        </div>

        <div class="perfil_contenido">
            <div class="perfil_inner">

                <div class="seccion_datos">
                    <h2>Información pública</h2>

                    <div class="datos_grid">
                        <div class="datos_item">
                            <label>Nombre</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["nombre"]) ?></span>
                        </div>

                        <div class="datos_item">
                            <label>Apellidos</label>
                            <span class="modo_vista"><?= htmlspecialchars($perfil["apellidos"]) ?></span>
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
                    <h2>Última actividad publicada</h2>

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
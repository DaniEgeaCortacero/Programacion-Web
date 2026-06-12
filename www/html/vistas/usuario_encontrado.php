<?php
$modo_usuario = $modo_usuario ?? "busqueda";

$foto = !empty($u["foto_perfil"])
    ? $u["foto_perfil"]
    : "../img/default.png";

$id_usuario_card = intval($u["id"]);
?>

<div class="card_usuario_encontrado">

    <img
        src="<?= htmlspecialchars($foto) ?>"
        class="foto_usuario_encontrado"
        alt="Foto de perfil">

    <div class="info_usuario_encontrado">
        <h3><?= htmlspecialchars($u["usuario"]) ?></h3>
        <p><?= htmlspecialchars($u["nombre"] . " " . $u["apellidos"]) ?></p>
    </div>

    <div class="acciones_usuario_encontrado">

        <?php if ($modo_usuario === "amistad"): ?>

            <a href="/html/prototipo_main.php?vista=amistad_detalles&id=<?= $id_usuario_card ?>"
            class="btn_circular btn_ver_usuario" 
            title="Ver perfil">
                👤
            </a>

            <button 
                type="button"
                class="btn_circular btn_anular_usuario"
                onclick="eliminarAmistad(<?= $id_usuario_card ?>)"
                title="Eliminar amistad">
                −
            </button>

        <?php elseif ($modo_usuario === "solicitud"): ?>

            <button
                class="btn_circular btn_agregar_usuario"
                onclick="aceptarAmistad(<?= $id_usuario_card ?>)"
                title="Aceptar solicitud">
                ✓
            </button>

            <button
                class="btn_circular btn_rechazar_usuario"
                onclick="rechazarAmistad(<?= $id_usuario_card ?>)"
                title="Rechazar solicitud">
                ✕
            </button>

            <a
                href="prototipo_main.php?vista=usuario_detalles&id=<?= $id_usuario_card ?>"
                class="btn_circular btn_ver_usuario"
                title="Ver perfil">
                👤
            </a>

        <?php else: ?>

            <button
                class="btn_circular btn_agregar_usuario"
                onclick="agregarAmigo(<?= $id_usuario_card ?>)"
                title="Agregar usuario">
                +
            </button>

            <a
                href="prototipo_main.php?vista=usuario_detalles&id=<?= $id_usuario_card ?>"
                class="btn_circular btn_ver_usuario"
                title="Ver perfil">
                👤
            </a>

        <?php endif; ?>

    </div>

</div>
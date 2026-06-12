<?php
require_once __DIR__ . "/../../controladores/admin_guard.php";
require_once __DIR__ . "/../../controladores/db.php";

$id_usuario = intval($_GET["id_usuario"] ?? 0);

if ($id_usuario <= 0) {
    echo "<p>Usuario no válido.</p>";
    return;
}

$sql_user = "SELECT id, usuario, nombre, apellidos
             FROM usuario
             WHERE id = ?
             LIMIT 1";

$stmt_user = $mysqli->prepare($sql_user);
$stmt_user->bind_param("i", $id_usuario);
$stmt_user->execute();
$res_user = $stmt_user->get_result();

if ($res_user->num_rows === 0) {
    echo "<p>Usuario no encontrado.</p>";
    return;
}

$usuario = $res_user->fetch_assoc();
$stmt_user->close();

$sql = "SELECT 
            id,
            nombre,
            ruta,
            alto,
            ancho,
            tamano,
            es_perfil
        FROM imagen
        WHERE id_usuario = ?
        ORDER BY es_perfil DESC, id DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$imagenes = $stmt->get_result();
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1>Gestión de imágenes</h1>
        <p>
            Imágenes de 
            <strong>@<?= htmlspecialchars($usuario["usuario"]) ?></strong>
            — <?= htmlspecialchars($usuario["nombre"] . " " . $usuario["apellidos"]) ?>
        </p>
    </div>

    <div class="admin_acciones_superiores">
        <a class="btn_tabla" href="prototipo_main.php?vista=admin_usuarios">
            Volver a usuarios
        </a>
    </div>

    <div class="admin_contenido">
        <?php if ($imagenes->num_rows === 0): ?>
            <p>Este usuario no tiene imágenes.</p>
        <?php else: ?>
            <div class="admin_grid_imagenes">
                <?php while ($img = $imagenes->fetch_assoc()): ?>
                    <article class="admin_img_card">
                        <div class="admin_img_preview">
                            <img 
                                src="<?= htmlspecialchars($img["ruta"]) ?>" 
                                alt="<?= htmlspecialchars($img["nombre"]) ?>"
                            >
                        </div>

                        <div class="admin_img_info">
                            <strong><?= htmlspecialchars($img["nombre"]) ?></strong>

                            <span>
                                <?= intval($img["ancho"]) ?>x<?= intval($img["alto"]) ?>
                            </span>

                            <?php if (intval($img["es_perfil"]) === 1): ?>
                                <span class="badge_perfil">Perfil</span>
                            <?php endif; ?>
                        </div>

                        <form 
                            method="POST" 
                            action="../controladores/admin_eliminar_imagen.php"
                            onsubmit="return confirm('¿Eliminar esta imagen?');"
                        >
                            <input type="hidden" name="id_imagen" value="<?= intval($img["id"]) ?>">
                            <input type="hidden" name="id_usuario" value="<?= intval($id_usuario) ?>">

                            <button 
                                type="submit" 
                                class="btn_tabla btn_peligro"
                                <?= intval($img["es_perfil"]) === 1 ? "disabled" : "" ?>
                            >
                                Eliminar
                            </button>
                        </form>
                    </article>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

</section>

<?php
$stmt->close();
?>
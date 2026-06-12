<?php
require_once __DIR__ . "/../../controladores/admin_guard.php";
require_once __DIR__ . "/../../controladores/db.php";

$busqueda = trim($_GET["buscar"] ?? "");

if ($busqueda !== "") {
    $like = "%" . $busqueda . "%";

    $sql = "SELECT id, usuario, correo, nombre, apellidos, fecha_baja
            FROM usuario
            WHERE usuario LIKE ?
            OR correo LIKE ?
            OR nombre LIKE ?
            OR apellidos LIKE ?
            ORDER BY id DESC";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssss", $like, $like, $like, $like);
} else {
    $sql = "SELECT id, usuario, correo, nombre, apellidos, fecha_baja
            FROM usuario
            ORDER BY id DESC";

    $stmt = $mysqli->prepare($sql);
}

$stmt->execute();
$usuarios = $stmt->get_result();
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1>Gestión de usuarios</h1>
        <p>Consulta, busca y gestiona los usuarios registrados.</p>
    </div>

    <form class="admin_busqueda" method="GET" action="prototipo_main.php">
        <input type="hidden" name="vista" value="admin_usuarios">

        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar usuario..." 
            value="<?= htmlspecialchars($busqueda) ?>"
        >
    </form>

    <div class="admin_contenido">
        <table class="admin_tabla">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($u = $usuarios->fetch_assoc()): ?>
                    <?php
                    $id_usuario_fila = intval($u["id"]);
                    $activo = empty($u["fecha_baja"]);
                    $es_usuario_actual = $id_usuario_fila === intval($_SESSION["id_usuario"]);
                    ?>

                    <tr class="fila_usuario_admin_info">
                        <td>
                            <div class="usuario_admin_info">
                                <strong><?= htmlspecialchars($u["nombre"] . " " . $u["apellidos"]) ?></strong>
                                <span>@<?= htmlspecialchars($u["usuario"]) ?></span>
                            </div>
                        </td>

                        <td><?= htmlspecialchars($u["correo"]) ?></td>

                        <td><?= $activo ? "Activo" : "Baja" ?></td>
                    </tr>

                    <tr class="fila_usuario_admin_acciones">
                        <td colspan="3">
                            <div class="acciones_usuario_admin">
                                <a 
                                    class="btn_tabla"
                                    href="prototipo_main.php?vista=admin_editar_usuario&id=<?= $id_usuario_fila ?>">
                                    Editar
                                </a>

                                <a 
                                    class="btn_tabla"
                                    href="prototipo_main.php?vista=admin_actividades&id_usuario=<?= $id_usuario_fila ?>">
                                    Actividades
                                </a>

                                <a 
                                    class="btn_tabla"
                                    href="prototipo_main.php?vista=admin_imagenes&id_usuario=<?= $id_usuario_fila ?>">
                                    Imágenes
                                </a>

                                <?php if (!$es_usuario_actual): ?>
                                    <?php if ($activo): ?>
                                        <form 
                                            method="POST" 
                                            action="../controladores/admin_baja_usuario.php"
                                            onsubmit="return confirm('¿Dar de baja este usuario?');"
                                        >
                                            <input type="hidden" name="id_usuario" value="<?= $id_usuario_fila ?>">
                                            <button type="submit" class="btn_tabla btn_peligro">Baja</button>
                                        </form>
                                    <?php else: ?>
                                        <form 
                                            method="POST" 
                                            action="../controladores/admin_reactivar_usuario.php"
                                            onsubmit="return confirm('¿Reactivar este usuario?');"
                                        >
                                            <input type="hidden" name="id_usuario" value="<?= $id_usuario_fila ?>">
                                            <button type="submit" class="btn_tabla">Reactivar</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>

                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</section>

<?php $stmt->close(); ?>
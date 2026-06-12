<?php
require_once __DIR__ . "/../../controladores/admin_guard.php";
require_once __DIR__ . "/../../controladores/db.php";

$busqueda = trim($_GET["buscar"] ?? "");
$id_usuario = intval($_GET["id_usuario"] ?? 0);

$usuario_filtrado = null;

if ($id_usuario > 0) {
    $sql_user = "SELECT id, usuario, nombre, apellidos
                 FROM usuario
                 WHERE id = ?
                 LIMIT 1";

    $stmt_user = $mysqli->prepare($sql_user);
    $stmt_user->bind_param("i", $id_usuario);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();
    $usuario_filtrado = $res_user->fetch_assoc();
    $stmt_user->close();
}

$where = [];
$params = [];
$types = "";

if ($id_usuario > 0) {
    $where[] = "a.id_usuario = ?";
    $params[] = $id_usuario;
    $types .= "i";
}

if ($busqueda !== "") {
    $where[] = "(a.titulo LIKE ? OR u.usuario LIKE ? OR u.nombre LIKE ? OR u.apellidos LIKE ?)";
    $like = "%" . $busqueda . "%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "ssss";
}

$where_sql = "";

if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$sql = "SELECT 
            a.id,
            a.titulo,
            a.fecha_evento,
            a.fecha_publicacion,
            a.archivo_gpx,
            ta.nombre AS tipo_actividad,
            u.id AS id_usuario,
            u.usuario,
            u.nombre,
            u.apellidos,

            (
                SELECT COUNT(*) 
                FROM actividad_imagen ai 
                WHERE ai.id_actividad = a.id
            ) AS total_imagenes,

            (
                SELECT COUNT(*) 
                FROM aplauso ap 
                WHERE ap.id_actividad = a.id
            ) AS total_aplausos

        FROM actividad a
        JOIN usuario u ON u.id = a.id_usuario
        JOIN tipo_actividad ta ON ta.id = a.id_tipo_actividad
        $where_sql
        ORDER BY a.fecha_publicacion DESC";

$stmt = $mysqli->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$actividades = $stmt->get_result();
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1>Gestión de actividades</h1>

        <?php if ($usuario_filtrado): ?>
            <p>
                Actividades de 
                <strong>@<?= htmlspecialchars($usuario_filtrado["usuario"]) ?></strong>
                — <?= htmlspecialchars($usuario_filtrado["nombre"] . " " . $usuario_filtrado["apellidos"]) ?>
            </p>
        <?php else: ?>
            <p>Consulta, busca, edita o elimina actividades de la plataforma.</p>
        <?php endif; ?>
    </div>

    <div class="admin_acciones_superiores">
        <a class="btn_tabla" href="prototipo_main.php?vista=admin_usuarios">
            Volver a usuarios
        </a>

        <a class="btn_tabla" href="prototipo_main.php?vista=admin_actividades">
            Ver todas
        </a>
    </div>

    <form class="admin_busqueda" method="GET" action="prototipo_main.php">
        <input type="hidden" name="vista" value="admin_actividades">

        <?php if ($id_usuario > 0): ?>
            <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">
        <?php endif; ?>

        <input 
            type="text" 
            name="buscar" 
            placeholder="Buscar por título o usuario..." 
            value="<?= htmlspecialchars($busqueda) ?>"
        >
    </form>

    <div class="admin_contenido">
        <table class="admin_tabla">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Usuario</th>
                    <th>Tipo</th>
                    <th>Fecha evento</th>
                    <th>Publicación</th>
                    <th>Imágenes</th>
                    <th>Aplausos</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($actividades->num_rows === 0): ?>
                    <tr>
                        <td colspan="8">No se han encontrado actividades.</td>
                    </tr>
                <?php endif; ?>

                <?php while ($a = $actividades->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($a["titulo"]) ?></strong>
                        </td>

                        <td>
                            <a 
                                href="prototipo_main.php?vista=amistad_detalles&id=<?= intval($a["id_usuario"]) ?>"
                                class="admin_link_usuario"
                            >
                                @<?= htmlspecialchars($a["usuario"]) ?>
                            </a>
                        </td>

                        <td><?= htmlspecialchars($a["tipo_actividad"]) ?></td>

                        <td><?= htmlspecialchars($a["fecha_evento"]) ?></td>

                        <td><?= htmlspecialchars($a["fecha_publicacion"]) ?></td>

                        <td><?= intval($a["total_imagenes"]) ?></td>

                        <td><?= intval($a["total_aplausos"]) ?></td>

                        <td>
                            <div class="acciones">
                                <button 
                                    type="button" 
                                    class="btn_tabla"
                                    onclick="abrirEvento(<?= intval($a["id"]) ?>)">
                                    Ver
                                </button>

                                <a 
                                    class="btn_tabla"
                                    href="prototipo_main.php?vista=editarEvento&id=<?= intval($a["id"]) ?>">
                                    Editar
                                </a>

                                <button 
                                    type="button" 
                                    class="btn_tabla btn_peligro"
                                    onclick="eliminarActividad(<?= intval($a["id"]) ?>)">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</section>

<?php
$stmt->close();
?>
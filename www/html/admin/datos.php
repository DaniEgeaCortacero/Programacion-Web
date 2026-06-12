<?php
require_once __DIR__ . "/../../controladores/admin_guard.php";
require_once __DIR__ . "/../../controladores/db.php";

$seccion = $_GET["seccion"] ?? "tipos";

$secciones_validas = ["tipos", "paises", "provincias", "localidades"];

if (!in_array($seccion, $secciones_validas)) {
    $seccion = "tipos";
}

function activo($actual, $valor) {
    return $actual === $valor ? "activo" : "";
}
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1>Gestión de datos auxiliares</h1>
        <p>Selecciona la categoría que quieres administrar.</p>
    </div>

    <div class="admin_subnav">
        <a class="<?= activo($seccion, "tipos") ?>" href="prototipo_main.php?vista=admin_datos&seccion=tipos">
            Tipos de actividad
        </a>

        <a class="<?= activo($seccion, "paises") ?>" href="prototipo_main.php?vista=admin_datos&seccion=paises">
            Países
        </a>

        <a class="<?= activo($seccion, "provincias") ?>" href="prototipo_main.php?vista=admin_datos&seccion=provincias">
            Provincias
        </a>

        <a class="<?= activo($seccion, "localidades") ?>" href="prototipo_main.php?vista=admin_datos&seccion=localidades">
            Localidades
        </a>
    </div>

    <div class="admin_contenido">

        <?php if ($seccion === "tipos"): ?>

            <?php $res = $mysqli->query("SELECT id, nombre FROM tipo_actividad ORDER BY nombre"); ?>

            <h2>Tipos de actividad</h2>

            <form class="admin_form_datos" method="POST" action="../controladores/admin_guardar_dato.php">
                <input type="hidden" name="seccion" value="tipos">
                <input type="text" name="nombre" placeholder="Nuevo tipo de actividad..." required>
                <button type="submit" class="btn_admin">Añadir</button>
            </form>

            <table class="admin_tabla">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($fila = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila["nombre"]) ?></td>
                            <td>
                                <div class="acciones_dato">
                                    <form method="POST" action="../controladores/admin_eliminar_dato.php" onsubmit="return confirm('¿Eliminar este tipo de actividad?');">
                                        <input type="hidden" name="seccion" value="tipos">
                                        <input type="hidden" name="id" value="<?= intval($fila["id"]) ?>">
                                        <a 
                                            class="btn_tabla"
                                            href="prototipo_main.php?vista=admin_editar_dato&seccion=tipos&id=<?= intval($fila["id"]) ?>">
                                            Editar
                                        </a>
                                        <button type="submit" class="btn_tabla btn_peligro">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($seccion === "paises"): ?>

            <?php $res = $mysqli->query("SELECT id, nombre, iso FROM pais ORDER BY nombre"); ?>

            <h2>Países</h2>

            <form class="admin_form_datos" method="POST" action="../controladores/admin_guardar_dato.php">
                <input type="hidden" name="seccion" value="paises">
                <input type="text" name="nombre" placeholder="Nombre del país..." required>
                <input type="text" name="iso" placeholder="ISO..." required>
                <button type="submit" class="btn_admin">Añadir</button>
            </form>

            <table class="admin_tabla">
                <thead>
                    <tr>
                        <th>País</th>
                        <th>ISO</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($fila = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila["nombre"]) ?></td>
                            <td><?= htmlspecialchars($fila["iso"]) ?></td>
                            <td>
                                <div class="acciones_dato">
                                    <form method="POST" action="../controladores/admin_eliminar_dato.php" onsubmit="return confirm('¿Eliminar este país?');">
                                        <input type="hidden" name="seccion" value="paises">
                                        <input type="hidden" name="id" value="<?= intval($fila["id"]) ?>">
                                        <a 
                                            class="btn_tabla"
                                            href="prototipo_main.php?vista=admin_editar_dato&seccion=paises&id=<?= intval($fila["id"]) ?>">
                                            Editar
                                        </a>
                                        <button type="submit" class="btn_tabla btn_peligro">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($seccion === "provincias"): ?>

            <?php
            $paises = $mysqli->query("SELECT id, nombre FROM pais ORDER BY nombre");

            $res = $mysqli->query("
                SELECT 
                    pr.id,
                    pr.nombre,
                    p.nombre AS pais
                FROM provincia pr
                JOIN pais p ON p.id = pr.id_pais
                ORDER BY p.nombre, pr.nombre
            ");
            ?>

            <h2>Provincias</h2>

            <form class="admin_form_datos" method="POST" action="../controladores/admin_guardar_dato.php">
                <input type="hidden" name="seccion" value="provincias">

                <input 
                    type="text" 
                    name="nombre" 
                    placeholder="Nueva provincia..." 
                    required
                >

                <select name="id_pais" required>
                    <option value="">Selecciona país</option>

                    <?php while ($pais = $paises->fetch_assoc()): ?>
                        <option value="<?= intval($pais["id"]) ?>">
                            <?= htmlspecialchars($pais["nombre"]) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="btn_admin">Añadir</button>
            </form>

            <table class="admin_tabla">
                <thead>
                    <tr>
                        <th>Provincia</th>
                        <th>País</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($fila = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila["nombre"]) ?></td>
                            <td><?= htmlspecialchars($fila["pais"]) ?></td>
                            <td>
                                <div class="acciones_dato">
                                    <form 
                                        method="POST" 
                                        action="../controladores/admin_eliminar_dato.php"
                                        onsubmit="return confirm('¿Eliminar esta provincia?');"
                                    >
                                        <input type="hidden" name="seccion" value="provincias">
                                        <input type="hidden" name="id" value="<?= intval($fila["id"]) ?>">
                                        <a 
                                            class="btn_tabla"
                                            href="prototipo_main.php?vista=admin_editar_dato&seccion=provincias&id=<?= intval($fila["id"]) ?>">
                                            Editar
                                        </a>
                                        <button type="submit" class="btn_tabla btn_peligro">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        
            <?php elseif ($seccion === "localidades"): ?>

                <?php
                $provincias = $mysqli->query("
                    SELECT 
                        pr.id,
                        pr.nombre,
                        p.nombre AS pais
                    FROM provincia pr
                    JOIN pais p ON p.id = pr.id_pais
                    ORDER BY p.nombre, pr.nombre
                ");

                $res = $mysqli->query("
                    SELECT 
                        l.id,
                        l.nombre,
                        pr.nombre AS provincia,
                        p.nombre AS pais
                    FROM localidad l
                    JOIN provincia pr ON pr.id = l.id_provincia
                    JOIN pais p ON p.id = pr.id_pais
                    ORDER BY p.nombre, pr.nombre, l.nombre
                ");
                ?>

                <h2>Localidades</h2>

                <form class="admin_form_datos" method="POST" action="../controladores/admin_guardar_dato.php">
                    <input type="hidden" name="seccion" value="localidades">

                    <input 
                        type="text" 
                        name="nombre" 
                        placeholder="Nueva localidad..." 
                        required
                    >

                    <select name="id_provincia" required>
                        <option value="">Selecciona provincia</option>

                        <?php while ($provincia = $provincias->fetch_assoc()): ?>
                            <option value="<?= intval($provincia["id"]) ?>">
                                <?= htmlspecialchars($provincia["pais"] . " - " . $provincia["nombre"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <button type="submit" class="btn_admin">Añadir</button>
                </form>

                <table class="admin_tabla">
                    <thead>
                        <tr>
                            <th>Localidad</th>
                            <th>Provincia</th>
                            <th>País</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while ($fila = $res->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($fila["nombre"]) ?></td>
                                <td><?= htmlspecialchars($fila["provincia"]) ?></td>
                                <td><?= htmlspecialchars($fila["pais"]) ?></td>
                                <td>
                                    <div class="acciones_dato">
                                        <form 
                                            method="POST" 
                                            action="../controladores/admin_eliminar_dato.php"
                                            onsubmit="return confirm('¿Eliminar esta localidad?');"
                                        >
                                            <input type="hidden" name="seccion" value="localidades">
                                            <input type="hidden" name="id" value="<?= intval($fila["id"]) ?>">
                                            <a 
                                                class="btn_tabla"
                                                href="prototipo_main.php?vista=admin_editar_dato&seccion=localidades&id=<?= intval($fila["id"]) ?>">
                                                Editar
                                            </a>
                                            <button type="submit" class="btn_tabla btn_peligro">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <?php else: ?>

                <p>La sección solicitada no existe.</p>

            <?php endif; ?>
    </div>

</section>
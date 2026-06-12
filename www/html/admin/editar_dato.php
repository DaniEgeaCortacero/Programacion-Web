<?php
require_once __DIR__ . "/../../controladores/admin_guard.php";
require_once __DIR__ . "/../../controladores/db.php";

$seccion = $_GET["seccion"] ?? "";
$id = intval($_GET["id"] ?? 0);

$secciones_validas = ["tipos", "paises", "provincias", "localidades"];

if (!in_array($seccion, $secciones_validas) || $id <= 0) {
    echo "<p>Dato no válido.</p>";
    return;
}

$dato = null;

if ($seccion === "tipos") {

    $sql = "SELECT id, nombre
            FROM tipo_actividad
            WHERE id = ?
            LIMIT 1";

} elseif ($seccion === "paises") {

    $sql = "SELECT id, nombre, iso
            FROM pais
            WHERE id = ?
            LIMIT 1";

} elseif ($seccion === "provincias") {

    $sql = "SELECT id, nombre, id_pais, id_ccaa
            FROM provincia
            WHERE id = ?
            LIMIT 1";

} elseif ($seccion === "localidades") {

    $sql = "SELECT id, nombre, id_provincia, cod_municipio, dc
            FROM localidad
            WHERE id = ?
            LIMIT 1";
}

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<p>No se ha encontrado el dato.</p>";
    return;
}

$dato = $res->fetch_assoc();
$stmt->close();

$paises = null;
$provincias = null;

if ($seccion === "provincias") {
    $paises = $mysqli->query("SELECT id, nombre FROM pais ORDER BY nombre");
}

if ($seccion === "localidades") {
    $provincias = $mysqli->query("
        SELECT 
            pr.id,
            pr.nombre,
            p.nombre AS pais
        FROM provincia pr
        JOIN pais p ON p.id = pr.id_pais
        ORDER BY p.nombre, pr.nombre
    ");
}

$titulos = [
    "tipos" => "Editar tipo de actividad",
    "paises" => "Editar país",
    "provincias" => "Editar provincia",
    "localidades" => "Editar localidad"
];
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1><?= htmlspecialchars($titulos[$seccion]) ?></h1>
        <p>Modifica los datos del elemento seleccionado.</p>
    </div>

    <div class="admin_contenido">

        <form 
            class="form_admin_dato"
            method="POST"
            action="../controladores/admin_actualizar_dato.php"
        >
            <input type="hidden" name="seccion" value="<?= htmlspecialchars($seccion) ?>">
            <input type="hidden" name="id" value="<?= intval($dato["id"]) ?>">

            <?php if ($seccion === "tipos"): ?>

                <div class="campo">
                    <label>Nombre</label>
                    <input 
                        type="text"
                        name="nombre"
                        value="<?= htmlspecialchars($dato["nombre"]) ?>"
                        required
                    >
                </div>

            <?php elseif ($seccion === "paises"): ?>

                <div class="grid_doble">
                    <div class="campo">
                        <label>Nombre</label>
                        <input 
                            type="text"
                            name="nombre"
                            value="<?= htmlspecialchars($dato["nombre"]) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>ISO</label>
                        <input 
                            type="text"
                            name="iso"
                            value="<?= htmlspecialchars($dato["iso"]) ?>"
                            required
                        >
                    </div>
                </div>

            <?php elseif ($seccion === "provincias"): ?>

                <div class="grid_doble">
                    <div class="campo">
                        <label>Nombre</label>
                        <input 
                            type="text"
                            name="nombre"
                            value="<?= htmlspecialchars($dato["nombre"]) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>ID CCAA</label>
                        <input 
                            type="number"
                            name="id_ccaa"
                            value="<?= intval($dato["id_ccaa"]) ?>"
                            min="0"
                            required
                        >
                    </div>
                </div>

                <div class="campo">
                    <label>País</label>
                    <select name="id_pais" required>
                        <?php while ($pais = $paises->fetch_assoc()): ?>
                            <option 
                                value="<?= intval($pais["id"]) ?>"
                                <?= intval($pais["id"]) === intval($dato["id_pais"]) ? "selected" : "" ?>
                            >
                                <?= htmlspecialchars($pais["nombre"]) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

            <?php elseif ($seccion === "localidades"): ?>

                <div class="grid_doble">
                    <div class="campo">
                        <label>Nombre</label>
                        <input 
                            type="text"
                            name="nombre"
                            value="<?= htmlspecialchars($dato["nombre"]) ?>"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>Provincia</label>
                        <select name="id_provincia" required>
                            <?php while ($provincia = $provincias->fetch_assoc()): ?>
                                <option 
                                    value="<?= intval($provincia["id"]) ?>"
                                    <?= intval($provincia["id"]) === intval($dato["id_provincia"]) ? "selected" : "" ?>
                                >
                                    <?= htmlspecialchars($provincia["pais"] . " - " . $provincia["nombre"]) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="grid_doble">
                    <div class="campo">
                        <label>Código municipio</label>
                        <input 
                            type="number"
                            name="cod_municipio"
                            value="<?= intval($dato["cod_municipio"]) ?>"
                            min="0"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label>DC</label>
                        <input 
                            type="number"
                            name="dc"
                            value="<?= intval($dato["dc"]) ?>"
                            min="0"
                            required
                        >
                    </div>
                </div>

            <?php endif; ?>

            <div class="panel_admin_form">
                <a 
                    class="btn_tabla"
                    href="prototipo_main.php?vista=admin_datos&seccion=<?= urlencode($seccion) ?>">
                    Volver
                </a>

                <button type="submit" class="btn_admin">
                    Guardar cambios
                </button>
            </div>
        </form>

    </div>

</section>
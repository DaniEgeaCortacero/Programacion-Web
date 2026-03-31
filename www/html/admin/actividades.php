<?php
$usuarioId = $_GET['usuario'] ?? null;
?>

<section class="admin_panel">

    <div class="admin_header">
        <h1>Actividades del usuario</h1>

        <?php if ($usuarioId): ?>
            <p>Mostrando actividades asociadas al usuario seleccionado.</p>
        <?php else: ?>
            <p>No se ha seleccionado ningún usuario.</p>
        <?php endif; ?>
    </div>

    <div class="admin_acciones_superiores">
        <a class="btn_tabla" href="../html/prototipo_main.php?vista=admin_usuarios">← Volver a usuarios</a>
    </div>

    <div class="admin_contenido">

        <?php if ($usuarioId): ?>
            <table class="admin_tabla">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Ruta por Ceuta</td>
                        <td>Ciclismo</td>
                        <td>31/03/2026</td>
                        <td>Activa</td>
                        <td class="acciones">
                            <a class="btn_tabla" href="#">Ver detalle</a>
                            <button type="button" class="btn_tabla">Editar</button>
                            <button type="button" class="btn_tabla btn_peligro">Eliminar</button>
                        </td>
                    </tr>

                    <tr>
                        <td>Prueba</td>
                        <td>Ciclismo</td>
                        <td>30/03/2026</td>
                        <td>Activa</td>
                        <td class="acciones">
                            <a class="btn_tabla" href="#">Ver detalle</a>
                            <button type="button" class="btn_tabla">Editar</button>
                            <button type="button" class="btn_tabla btn_peligro">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>

        <?php else: ?>
            <div class="admin_mensaje">
                <p>Selecciona un usuario desde la vista de gestión de usuarios para ver sus actividades.</p>
            </div>
        <?php endif; ?>

    </div>

</section>
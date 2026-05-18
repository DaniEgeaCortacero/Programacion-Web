<?php
session_start();
require_once "../modelo/conexion.php";

$id_usuario_actual = $_SESSION["id_usuario"];
$busqueda = $_GET["busqueda"] ?? "";

$sql = "SELECT id, usuario, nombre, apellidos
        FROM usuario
        WHERE 
            (usuario LIKE ? OR nombre LIKE ? OR apellidos LIKE ?)
            AND id != ?
        LIMIT 10";

$stmt = $conexion->prepare($sql);

$like = "%$busqueda%";
$stmt->bind_param("sssi", $like, $like, $like, $id_usuario_actual);
$stmt->execute();

$resultado = $stmt->get_result();

while ($u = $resultado->fetch_assoc()) {
    echo '
        <div class="resultado_usuario">
            <div>
                <strong>' . htmlspecialchars($u["usuario"]) . '</strong>
                <p>' . htmlspecialchars($u["nombre"] . " " . $u["apellidos"]) . '</p>
            </div>

            <button onclick="agregarAmigo(' . intval($u["id"]) . ')">
                Agregar
            </button>
        </div>
    ';
}
?>
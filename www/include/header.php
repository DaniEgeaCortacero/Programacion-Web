<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../controladores/db.php";

$imagen_header = "../img/user_icon_green.png";

if (isset($_SESSION["id_usuario"])) {
    $id_usuario = intval($_SESSION["id_usuario"]);

    $sql = "SELECT ruta
            FROM imagen
            WHERE id_usuario = ?
            AND es_perfil = 1
            LIMIT 1";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        if (!empty($fila["ruta"])) {
            $ruta = $fila["ruta"];

            if (str_starts_with($ruta, "../") || str_starts_with($ruta, "/")) {
                $imagen_header = $ruta;
            } else {
                $imagen_header = "../" . $ruta;
            }
        }
    }

    $stmt->close();
}
?>

<header>
    <div class="headerLeft">
        <button id="toggleSidebar" class="btnMenu">☰</button>

        <a href="../html/prototipo_main.php">
            <img src="../img/logo-2-bn.png" alt="TrailSync" class="logo">
        </a>
    </div>

    <nav class="nav">
        <a class="btnItem" href="../html/prototipo_main.php?vista=crearEvento">Añadir evento</a>
    </nav>

    <button id="toggleProfileMenu" class="toggleProfileMenu">
        <img 
            src="<?= htmlspecialchars($imagen_header) ?>" 
            class="img_perfil" 
            alt="Foto de perfil"
        >
    </button>

    <div id="profileDropdown" class="profileDropdown">
        <a href="prototipo_main.php?vista=perfil">Perfil</a>
        <a href="prototipo_main.php?vista=configuracion">Configuración</a>
        <a href="../controladores/logout.php">Cerrar sesión</a>
    </div>
</header>
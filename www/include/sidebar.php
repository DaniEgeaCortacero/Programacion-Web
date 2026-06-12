<aside id="sidebar" class="sidebar">
    <nav>
      <ul class="menu">
        <li><a href="../html/prototipo_main.php?vista=home">Inicio</a></li>

        <?php if (isset($_SESSION["id_rol"]) && intval($_SESSION["id_rol"]) === 1): ?>
          <li class="submenu_item">
          <button class="submenu-btn" type="button">ADMINISTRADOR ▾</button>
            <ul class="submenu">
              <li><a href="../html/prototipo_main.php?vista=admin_administracion">ADMINISTRACION</a></li>
              <li><a href="../html/prototipo_main.php?vista=admin_datos">DATOS</a></li>
              <li><a href="../html/prototipo_main.php?vista=admin_usuarios">USUARIOS</a></li>
              <li><a href="../html/prototipo_main.php?vista=admin_actividades">ACTIVIDADES</a></li>
            </ul>
        </li>
        <?php endif; ?>

        <li><a href="../index.php">INDEX</a></li>  <!-- pagina nueva: target="_blank" -->
      </ul>
    </nav>
  </aside>
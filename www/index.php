<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mi web en Docker</title>
  <link rel="stylesheet" href="css/style.css?v=1">
</head>
<body>

  <!-- Menú superior -->
  <header class="topbar">
    <button id="toggleSidebar" class="btn">☰</button>
    <nav class="topnav">
      <ul>
        <li><a href="http://localhost:8082/html/prototipo_main.php">Prototipo</a></li>
        <li><a href="http://localhost:8082/html/prototipo_login.php?modo=login">Login</a></li>
      </ul>
    </nav>
  </header>

  <!-- Menú lateral izquierdo -->
  <aside id="sidebar" class="sidebar">
    <nav>
      <ul class="menu">
        <li><a href="index.php">Inicio</a></li>

        <li class="has-submenu">
          <button class="submenu-btn" type="button">Usuarios ▾</button>
          <ul class="submenu">
            <li><a href="usuarios.php">Ver usuarios</a></li>
            <li><a href="#">Crear usuario</a></li>
          </ul>
        </li>

        <li class="has-submenu">
          <button class="submenu-btn" type="button">Ajustes ▾</button>
          <ul class="submenu">
            <li><a href="#">Perfil</a></li>
            <li><a href="#">Seguridad</a></li>
          </ul>
        </li>

        <li><a href="https://www.google.es" target="_blank">Google (sidebar)</a></li>
      </ul>
    </nav>
  </aside>

  <main class="content">
    <h1>Hola desde Apache + PHP en Docker</h1>
    <p>Esto está saliendo desde <code>./www/index.php</code></p>

    <button id="prueba_btn">Probar JS</button>
    <p id="out"></p>
  </main>


<script src="js/app.js?v=1"></script>
</body>
</html>

<?php
$modo = $_GET['modo'] ?? 'login';
?>

<!doctype html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prototipo web</title>
    <link rel="stylesheet" href="../css/style_proto_login.css?v=30">
</head>
<body>
    <?php if ($modo == "login"): ?>
    <main class="login_card login">
        <div class="titulo">
            <h1>LOGIN</h1>
        </div>

        <div class="contenido">
            <form>
                <div>
                    <label>Usuario/Email:</label>
                    <input required>
                </div>
                <div>
                    <label>Contraseña:</label>
                    <input type="password" required>
                </div>
            </form>
        </div>

        <div class="panel">
            <a class="btn_register" href="http://localhost:8082/html/prototipo_login.php?modo=registro">Registrarse</a>
            <a class="btn_main" href="http://localhost:8082/html/prototipo_main.php">Siguiente</a>
        </div>
    </main>

    <?php elseif ($modo == "registro"): ?>
    <main class="login_card registro">
        <div class="titulo">
            <h1>REGISTRARSE</h1>
        </div>

        <div class="contenido">
            <form>
                <div class="campos_principales">
                    <div>
                        <label>Email:</label>
                        <input type="email" required>
                    </div>
                    <div>
                        <label>Nombre de usuario:</label>
                        <input type="email" required>
                    </div>
                    <div>
                        <label>Contraseña:</label>
                        <input type="password" required>
                    </div>
                    <div>
                        <label>Confirmar contraseña:</label>
                        <input type="password" required>
                    </div>
                </div>
                <div class="campos_secundarios">
                    <div>
                        <label>Nombre:</label>
                        <input type="text" required>
                    </div>
                    <div>
                        <label>Apellidos:</label>
                        <input type="text" required>
                    </div>
                    <div>
                        <label>Fecha de nacimiento:</label>
                        <input type="date" required>
                    </div>
                    <div>
                        <label>Tipo de actividad preferida:</label>
                        <input type="text" required>
                    </div>
                    <div>
                        <label>Localidad:</label>
                        <input type="text" required>
                    </div>
                    <div>
                        <label>Provincia:</label>
                        <input type="text" required>
                    </div>
                    <div>
                        <label>País:</label>
                        <input type="text" required>
                    </div>
                </div>
                
            </form>
        </div>

        <div class="panel">
            <a class="btn_volver" href="http://localhost:8082/html/prototipo_login.php?modo=login">Volver</a>
            <a class="btn_crear" href="http://localhost:8082/html/prototipo_main.php">Registrarse</a>
        </div>
    </main>
    <?php endif; ?>

</body>
</html>
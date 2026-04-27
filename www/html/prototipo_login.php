<?php
$modo = $_GET['modo'] ?? 'login';
?>

<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prototipo web</title>
    <link rel="stylesheet" href="../css/style_proto_login.css?v=16">
</head>

<body>
    <div class="acceso">
        <header class="marca">
            <img src="../img/logo-2-bn.png" alt="TrailSync">
            <p>"Sync your ride"</p>
        </header>

        <?php if ($modo == "login"): ?>
            <main class="login_card login">
                <div class="titulo">
                    <h1>LOGIN</h1>
                </div>

                <form action="../controladores/login.php" method="post" id="form_login">
                    <div class="contenido">
                        <div class="campo">
                            <label>Usuario/Email:</label>
                            <input required name="usuario">
                        </div>
                        <div class="campo">
                            <label>Contraseña:</label>
                            <input type="password" required name="clave">
                        </div>
                    </div>

                    <div class="panel login">
                        <a class="btn_register" href="./prototipo_login.php?modo=registro">Registrarse</a>
                        <button type="submit" class="btn_main" href="./prototipo_main.php">Siguiente</button>
                    </div>
                </form>


            </main>

        <?php elseif ($modo == "registro"): ?>
            <main class="login_card registro">
                <div class="titulo">
                    <h1>REGISTRARSE</h1>
                </div>

                <form action="../controladores/registro.php" method="post" id="form_registro">
                    <div class="contenido">
                        <div class="campos_principales">
                            <h3>Datos de acceso</h3>
                            <div class="campo">
                                <label>Email:</label>
                                <input type="email" name="correo" required>
                            </div>
                            <div class="campo">
                                <label>Nombre de usuario:</label>
                                <input type="text" name="usuario" required>
                            </div>
                            <div class="fila_doble">
                                <div class="campo">
                                    <label>Contraseña:</label>
                                    <input type="password" name="clave" required>
                                </div>
                                <div class="campo">
                                    <label>Confirmar contraseña:</label>
                                    <input type="password" name="clave2" required>
                                </div>
                            </div>
                        </div>
                        <div class="campos_secundarios">
                            <h3>Información personal</h3>
                            <div class="fila_doble">
                                <div class="campo">
                                    <label>Nombre:</label>
                                    <input type="text" name="nombre" required>
                                </div>
                                <div class="campo">
                                    <label>Apellidos:</label>
                                    <input type="text" name="apellidos" required>
                                </div>
                            </div>
                            <div class="campo">
                                <label>Tipo de actividad preferida:</label>
                                <input type="" name="actividad_favorita" required>
                            </div>
                            <div class="fila_doble">
                                <div class="campo">
                                    <label>Fecha de nacimiento:</label>
                                    <input type="date" name="fecha_nacimiento" required>
                                </div>
                                <div class="campo">
                                    <label>País:</label>
                                    <select>
                                        <option>España</option>
                                    </select>
                                </div>
                            </div>
                            <div class="fila_doble">
                                <div class="campo">
                                    <label>Provincia:</label>
                                    <select>
                                        <option>Madrid</option>
                                    </select>
                                </div>
                                <div class="campo">
                                    <label>Localidad:</label>
                                    <select>
                                        <option>Madrid</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="panel registro">
                        <a class="btn_volver" href="./prototipo_login.php?modo=login">Volver</a>
                        <button type="submit" class="btn_crear">Registrarse</button>
                    </div>
                </form>


            </main>
        <?php endif; ?>
    </div>
</body>

</html>
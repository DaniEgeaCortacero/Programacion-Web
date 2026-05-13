<!doctype html>
<html>

<head>
    <title>Cliente NuSOAP</title>
    <meta charset="utf-8" />
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 40px;
        }

        h1 {
            margin-bottom: 30px;
            color: #222;
        }

        .contenedor {
            max-width: 700px;
        }

        form {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            box-sizing: border-box;
            font-size: 14px;
        }

        button {
            background-color: #d76400;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        button:hover {
            background-color: #a04b00;
        }

        .resultado {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .resultado h2 {
            margin-top: 0;
            margin-bottom: 15px;
        }

        .usuario {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .usuario:last-child {
            border-bottom: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background-color: #d76400;
            color: white;
            text-align: left;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <h2>Panel de Control Web Service</h2>

    <form method="post">

        <label>Nombre:</label>
        <input type="text" name="nombre">

        <br><br>

        <label>Apellidos:</label>
        <input type="text" name="apellidos">

        <br><br>

        <button type="submit" name="accion">
            Buscar usuarios
        </button>

    </form>

    <?php
    require_once("lib/nusoap.php");
    error_reporting(E_ALL & ~E_DEPRECATED);

    // Inicializamos el cliente
    $cliente = new nusoap_client('http://localhost/ws/servidor.php?wsdl', 'wsdl');
    $cliente->soap_defencoding = 'UTF-8';
    $cliente->decode_utf8 = FALSE;

    $err = $cliente->getError();
    if ($err) {
        echo '<div class="resultado"><h2>Error de construcción</h2><pre>' . $err . '</pre></div>';
        die();
    }

    // Lógica de ejecución según el botón pulsado
    if (isset($_POST['accion'])) {

        $resultado = null;

        $parametros = array(
            'nombre' => $_POST['nombre'],
            'apellidos' => $_POST['apellidos']
        );

        $resultado = $cliente->call('ObtenerUsuarios', $parametros);

        // Manejo de errores de la llamada
        if ($cliente->fault) {
            echo '<div class="resultado"><h2>Fallo (Fault)</h2><pre>';
            print_r($resultado);
            echo '</pre></div>';
        } else {
            $err = $cliente->getError();
            if ($err) {
                echo '<div class="resultado"><h2>Error en la respuesta</h2><pre>' . $err . '</pre></div>';
            } else {
                echo '<div class="resultado"><h3>Resultado del Servidor:</h3>';

                if ($resultado == "No se encontraron usuarios.") {
                    echo "<p>No se encontraron usuarios.</p>";
                } else {

                    echo "<table>";
                    echo "<tr>";
                    echo "<th>Nombre</th>";
                    echo "<th>Apellidos</th>";
                    echo "<th>Actividad preferida</th>";
                    echo "</tr>";

                    $lineas = explode("\n", trim($resultado));

                    foreach ($lineas as $linea) {

                        if (trim($linea) == "") {
                            continue;
                        }

                        $partes = explode(" - ", $linea);

                        $nombreCompleto = $partes[0];
                        $actividad = isset($partes[1]) ? $partes[1] : "";

                        $nombrePartes = explode(" ", $nombreCompleto, 2);

                        $nombre = $nombrePartes[0];
                        $apellidos = isset($nombrePartes[1]) ? $nombrePartes[1] : "";

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($nombre) . "</td>";
                        echo "<td>" . htmlspecialchars($apellidos) . "</td>";
                        echo "<td>" . htmlspecialchars($actividad) . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                }

                echo '</div>';
            }
        }
    }
    ?>
</body>

</html>
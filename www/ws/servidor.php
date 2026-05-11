<?php 
// Desactivamos la visualización de errores directos para evitar que corrompan el XML
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_DEPRECATED);

require_once("lib/nusoap.php");

$namespace = "www.ugr.es";
 
// Creamos un soap server
$server = new soap_server();
 
$server->soap_defencoding = 'utf-8';
$server->decode_utf8 = false;
 
// Configuramos nuestro WSDL
$server->configureWSDL("PruebaWsdl", $namespace);
 
// Instanciamos nuestro namespace
$server->wsdl->schemaTargetNamespace = $namespace;
 
// Registramos nuestro primer método
$server->register(
    'ObtenerUsuarios',
    array(
        'nombre' => 'xsd:string',
        'apellidos' => 'xsd:string'
    ),
    array(
        'return' => 'xsd:string'
    ),
    $namespace,
    false,
    'rpc',
    'encoded',
    'Busca usuarios por nombre y apellidos'
);
 
// Implementación de las funciones
function ObtenerUsuarios($nombre, $apellidos){

    $conexion = new mysqli(
        "db",
        "practica",
        "practica",
        "practica"
    );

    if($conexion->connect_error){
        return "Error de conexión: " . $conexion->connect_error;
    }

    $sql = "
        SELECT u.nombre, u.apellidos, ta.nombre AS actividad
        FROM usuario u
        LEFT JOIN tipo_actividad ta 
            ON u.id_tipo_actividad_preferida = ta.id
        WHERE u.nombre LIKE ?
        AND u.apellidos LIKE ?
    ";

    $stmt = $conexion->prepare($sql);

    if(!$stmt){
        return "Error en prepare: " . $conexion->error;
    }

    $nombreBusqueda = "%" . $nombre . "%";
    $apellidosBusqueda = "%" . $apellidos . "%";

    $stmt->bind_param(
        "ss",
        $nombreBusqueda,
        $apellidosBusqueda
    );

    if(!$stmt->execute()){
        return "Error en execute: " . $stmt->error;
    }

    $stmt->bind_result($nombreBD, $apellidosBD, $tipoActividadBD);

    $resultado = "";

    while($stmt->fetch()){

        $resultado .=
            $nombreBD . " " .
            $apellidosBD . " - " .
            $tipoActividadBD . "\n";
    }

    if($resultado == ""){
        return "No se encontraron usuarios.";
    }

    return $resultado;
}
 
// Cambiamos la forma de obtener los datos POST para mayor compatibilidad
$POST_DATA = file_get_contents("php://input");
 
// Pasamos los datos al servicio
$server->service($POST_DATA);
exit();
?>

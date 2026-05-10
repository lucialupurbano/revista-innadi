<?php
$host = "localhost";
$usuario = "root";
$password = "";
$bd = "revista_arcana";

$conexion = new mysqli($host, $usuario, $password, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>

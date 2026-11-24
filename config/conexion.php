<?php
$host = "localhost";
$user = "root";
$clave = "";
$bd = "biblioteca_db";

$conexion = mysqli_connect($host, $user, $clave, $bd);

if (mysqli_connect_errno()){
    die("Error de conexión a la base de datos: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");

// Valores por defecto para evitar que la página falle si la tabla no existe
$configSistema = [
    'nombre_sistema' => 'Sistema de Biblioteca',
    'imagen_sistema' => 'assets/img/logo_default.png'
];

$queryConfig = mysqli_query($conexion, "SELECT * FROM configuracion WHERE id = 1");
if ($queryConfig && $row = mysqli_fetch_assoc($queryConfig)) {
    if (!empty($row['nombre_sistema'])) {
        $configSistema['nombre_sistema'] = $row['nombre_sistema'];
    }
    if (!empty($row['imagen_sistema'])) {
        $configSistema['imagen_sistema'] = $row['imagen_sistema'];
    }
}
?>
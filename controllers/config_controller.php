<?php
session_start();
require_once '../config/conexion.php';

// Verificar que sea administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../views/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sistema = trim($_POST['nombre_sistema']);
    $rif = trim($_POST['rif']);
    $direccion = trim($_POST['direccion']);

    // Obtener la imagen actual de la base de datos
    $res = mysqli_query($conexion, "SELECT imagen_sistema FROM configuracion LIMIT 1");
    $configActual = mysqli_fetch_assoc($res);
    $imagen_sistema = $configActual['imagen_sistema'] ?? 'assets/img/default.png';

    // Manejar la subida de la nueva imagen si se seleccionó una
    if (isset($_FILES['imagen_sistema']) && $_FILES['imagen_sistema']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = time() . '_' . basename($_FILES['imagen_sistema']['name']);
        $rutaDestino = '../assets/img/' . $nombreArchivo;
        
        // Asegurar que la carpeta exista
        if (!file_exists('../assets/img/')) {
            mkdir('../assets/img/', 0777, true);
        }

        if (move_uploaded_file($_FILES['imagen_sistema']['tmp_name'], $rutaDestino)) {
            $imagen_sistema = 'assets/img/' . $nombreArchivo;
        }
    }

    // Actualizar en la base de datos (usando ID 1 o la primera fila)
    $stmt = mysqli_prepare($conexion, "UPDATE configuracion SET nombre_sistema = ?, rif = ?, direccion = ?, imagen_sistema = ? WHERE id = 1");
    if (!$stmt) {
        // Si no tiene ID 1, intentamos actualizar la primera fila disponible
        $stmt = mysqli_prepare($conexion, "UPDATE configuracion SET nombre_sistema = ?, rif = ?, direccion = ?, imagen_sistema = ? LIMIT 1");
    }
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $nombre_sistema, $rif, $direccion, $imagen_sistema);
        mysqli_stmt_execute($stmt);
    }

    header("Location: ../views/configuracion.php?actualizado=1");
    exit;
}

header("Location: ../views/configuracion.php");
exit;
?>
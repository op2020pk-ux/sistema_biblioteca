<?php
session_start();
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // 1. Crear nuevo libro con validación de código duplicado
    if ($accion === 'crear') {
        $codigo       = trim($_POST['codigo']);
        $titulo       = trim($_POST['titulo']);
        $autor        = trim($_POST['autor']);
        $id_categoria = intval($_POST['id_categoria']);
        $tipo         = trim($_POST['tipo']);
        $stock        = intval($_POST['stock'] ?? 1);
        $archivo_pdf  = '';

        // Verificar si el código ya existe en la base de datos (activo o inactivo)
        $stmtCheck = mysqli_prepare($conexion, "SELECT id FROM libros WHERE codigo = ? LIMIT 1");
        mysqli_stmt_bind_param($stmtCheck, "s", $codigo);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            // El código ya está registrado, regresamos con error
            header("Location: ../views/libros.php?error=duplicado");
            exit;
        }

        // Manejo de archivo PDF si es digital
        if ($tipo === 'digital' && isset($_FILES['archivo_pdf']) && $_FILES['archivo_pdf']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = time() . '_' . basename($_FILES['archivo_pdf']['name']);
            $rutaDestino = '../assets/pdf/' . $nombreArchivo;
            
            if (!file_exists('../assets/pdf/')) {
                mkdir('../assets/pdf/', 0777, true);
            }

            if (move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $rutaDestino)) {
                $archivo_pdf = 'assets/pdf/' . $nombreArchivo;
            }
        }

        $stmt = mysqli_prepare($conexion, "INSERT INTO libros (codigo, titulo, autor, id_categoria, tipo, stock, archivo_pdf, estado) VALUES (?, ?, ?, ?, ?, ?, ?, 'activo')");
        mysqli_stmt_bind_param($stmt, "sssisds", $codigo, $titulo, $autor, $id_categoria, $tipo, $stock, $archivo_pdf);
        mysqli_stmt_execute($stmt);

        header("Location: ../views/libros.php?success=1");
        exit;
    }

    // 2. Cambiar estado (Desactivar / Reactivar)
    if ($accion === 'cambiar_estado') {
        $id     = intval($_POST['id']);
        $estado = trim($_POST['estado']);

        $stmt = mysqli_prepare($conexion, "UPDATE libros SET estado = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $estado, $id);
        mysqli_stmt_execute($stmt);

        header("Location: ../views/libros.php");
        exit;
    }
}

header("Location: ../views/libros.php");
exit;
?>
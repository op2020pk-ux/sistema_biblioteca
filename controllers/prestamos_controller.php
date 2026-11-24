<?php
session_start();
require_once '../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // 1. Registrar Préstamo
    if ($accion === 'prestar') {
        $id_estudiante = intval($_POST['id_estudiante']);
        $id_libro      = intval($_POST['id_libro']);
        $fecha_dev     = trim($_POST['fecha_devolucion_estimada']);
        $id_usuario    = intval($_SESSION['usuario_id']);

        mysqli_begin_transaction($conexion);

        try {
            // Verificar stock actual con bloqueo de fila
            $stmt = mysqli_prepare($conexion, "SELECT stock FROM libros WHERE id = ? FOR UPDATE");
            mysqli_stmt_bind_param($stmt, "i", $id_libro);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $libroData = mysqli_fetch_assoc($res);
            $stock = $libroData['stock'] ?? 0;

            if ($stock > 0) {
                // Registrar el préstamo
                $stmtIns = mysqli_prepare($conexion, "INSERT INTO prestamos (id_estudiante, id_libro, id_usuario, fecha_prestamo, fecha_devolucion_estimada, estado) VALUES (?, ?, ?, CURDATE(), ?, 'prestado')");
                mysqli_stmt_bind_param($stmtIns, "iiis", $id_estudiante, $id_libro, $id_usuario, $fecha_dev);
                mysqli_stmt_execute($stmtIns);
                
                $id_nuevo_prestamo = mysqli_insert_id($conexion);

                // Descontar stock
                $stmtUpd = mysqli_prepare($conexion, "UPDATE libros SET stock = stock - 1 WHERE id = ?");
                mysqli_stmt_bind_param($stmtUpd, "i", $id_libro);
                mysqli_stmt_execute($stmtUpd);

                // --- GENERAR COMPROBANTE EN PDF (HTML) AUTOMÁTICAMENTE ANTES DEL COMMIT ---
                $queryDoc = "SELECT p.*, 
                                    e.nombre_completo AS estudiante_nombre, e.cedula AS estudiante_cedula, e.telefono AS estudiante_telefono, e.edad AS estudiante_edad,
                                    l.codigo AS libro_codigo, l.titulo AS libro_titulo, l.autor AS libro_autor,
                                    u.nombre AS operador_nombre
                             FROM prestamos p
                             JOIN estudiantes e ON p.id_estudiante = e.id
                             JOIN libros l ON p.id_libro = l.id
                             JOIN usuarios u ON p.id_usuario = u.id
                             WHERE p.id = ? LIMIT 1";
                $stmtDoc = mysqli_prepare($conexion, $queryDoc);
                mysqli_stmt_bind_param($stmtDoc, "i", $id_nuevo_prestamo);
                mysqli_stmt_execute($stmtDoc);
                $dataDoc = mysqli_fetch_assoc(mysqli_stmt_get_result($stmtDoc));

                // Obtener configuración de la biblioteca
                $resConfig = mysqli_query($conexion, "SELECT * FROM configuracion LIMIT 1");
                $config = mysqli_fetch_assoc($resConfig);
                $nombreBiblio = $config['nombre_sistema'] ?? 'Biblioteca Digital y Física';
                $rifBiblio = $config['rif'] ?? 'J-00000000-0';
                $dirBiblio = $config['direccion'] ?? 'Dirección no especificada';

                if ($dataDoc) {
                    $fechaPrestamo = $dataDoc['fecha_prestamo'];
                    
                    // Asegurar ruta física correcta en la raíz del proyecto
                    $carpetaAnioMes = '../pdf_prestamos/' . date('Y-m', strtotime($fechaPrestamo)) . '/';
                    
                    if (!file_exists($carpetaAnioMes)) {
                        mkdir($carpetaAnioMes, 0777, true);
                    }

                    $nombreArchivoPDF = 'Comprobante_Prestamo_' . $dataDoc['id'] . '_' . date('Ymd', strtotime($fechaPrestamo)) . '.html';
                    $rutaCompleta = $carpetaAnioMes . $nombreArchivoPDF;

                    $htmlFactura = '
                    <!DOCTYPE html>
                    <html lang="es">
                    <head>
                        <meta charset="UTF-8">
                        <title>Comprobante de Préstamo #' . $dataDoc['id'] . '</title>
                        <style>
                            body { font-family: Arial, sans-serif; color: #333; margin: 40px; line-height: 1.5; }
                            .header { text-align: center; border-bottom: 2px solid #2a5298; padding-bottom: 15px; margin-bottom: 20px; }
                            .header h2 { margin: 0; color: #1e3c72; }
                            .header p { margin: 3px 0; font-size: 14px; color: #666; }
                            .info-section { margin-bottom: 20px; }
                            .info-section h4 { background: #f4f6f9; padding: 8px; border-left: 4px solid #2a5298; margin-bottom: 10px; }
                            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                            th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
                            th { background-color: #f8f9fa; }
                            .condiciones { background: #fff8e1; border: 1px solid #ffe082; padding: 15px; border-radius: 5px; font-size: 13px; margin-top: 20px; }
                            .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <h2>' . htmlspecialchars($nombreBiblio) . '</h2>
                            <p><strong>RIF:</strong> ' . htmlspecialchars($rifBiblio) . '</p>
                            <p><strong>Dirección:</strong> ' . htmlspecialchars($dirBiblio) . '</p>
                            <h3 style="margin-top: 15px; color: #333;">COMPROBANTE DE PRÉSTAMO DE LIBRO FÍSICO</h3>
                            <p>Comprobante N°: <strong>0000' . $dataDoc['id'] . '</strong></p>
                        </div>
                        <div class="info-section">
                            <h4>1. Datos del Solicitante (Estudiante)</h4>
                            <table>
                                <tr><th width="30%">Nombre Completo:</th><td>' . htmlspecialchars($dataDoc['estudiante_nombre']) . '</td></tr>
                                <tr><th>Cédula / ID:</th><td>' . htmlspecialchars($dataDoc['estudiante_cedula']) . '</td></tr>
                                <tr><th>Edad:</th><td>' . (!empty($dataDoc['estudiante_edad']) ? $dataDoc['estudiante_edad'] . ' años' : 'No registrada') . '</td></tr>
                                <tr><th>Teléfono:</th><td>' . htmlspecialchars($dataDoc['estudiante_telefono']) . '</td></tr>
                            </table>
                        </div>
                        <div class="info-section">
                            <h4>2. Datos del Libro y Vigencia del Préstamo</h4>
                            <table>
                                <tr><th width="30%">Título del Libro:</th><td><strong>' . htmlspecialchars($dataDoc['libro_titulo']) . '</strong> (Código: ' . htmlspecialchars($dataDoc['libro_codigo']) . ')</td></tr>
                                <tr><th>Autor:</th><td>' . htmlspecialchars($dataDoc['libro_autor']) . '</td></tr>
                                <tr><th>Tipo de Material:</th><td>Libro Físico</td></tr>
                                <tr><th>Fecha de Préstamo (Desde):</th><td>' . $dataDoc['fecha_prestamo'] . '</td></tr>
                                <tr><th>Fecha Devolución Estimada (Hasta):</th><td><strong>' . $dataDoc['fecha_devolucion_estimada'] . '</strong></td></tr>
                                <tr><th>Atendido por (Operador):</th><td>' . htmlspecialchars($dataDoc['operador_nombre']) . '</td></tr>
                            </table>
                        </div>
                        <div class="condiciones">
                            <strong>Condiciones y Políticas de Préstamo:</strong>
                            <ul>
                                <li>El libro debe ser devuelto en la fecha estimada indicada en este comprobante.</li>
                                <li>El material debe conservarse en óptimas condiciones, libre de rayones, roturas o humedad.</li>
                                <li>El incumplimiento en la devolución o el daño del material físico acarreará suspensiones temporales según el reglamento interno.</li>
                            </ul>
                        </div>
                        <div class="footer">
                            <p>Documento generado automáticamente por el Sistema de Gestión de Biblioteca el ' . date('Y-m-d H:i:s') . '</p>
                        </div>
                    </body>
                    </html>';

                    file_put_contents($rutaCompleta, $htmlFactura);
                }

                // Confirmar transacción de la base de datos
                mysqli_commit($conexion);

                header("Location: ../views/prestamos.php");
                exit;
            } else {
                mysqli_rollback($conexion);
                header("Location: ../views/prestamos.php?error=sin_stock");
                exit;
            }
        } catch (Exception $e) {
            mysqli_rollback($conexion);
            header("Location: ../views/prestamos.php?error=error_sistema");
            exit;
        }
    }

    // 2. Marcar Devolución
    if ($accion === 'devolver') {
        $id_prestamo = intval($_POST['id_prestamo']);
        $id_libro    = intval($_POST['id_libro']);

        mysqli_begin_transaction($conexion);

        try {
            $stmtDev = mysqli_prepare($conexion, "UPDATE prestamos SET estado = 'devuelto', fecha_devolucion_real = CURDATE() WHERE id = ?");
            mysqli_stmt_bind_param($stmtDev, "i", $id_prestamo);
            mysqli_stmt_execute($stmtDev);

            $stmtDevStock = mysqli_prepare($conexion, "UPDATE libros SET stock = stock + 1 WHERE id = ?");
            mysqli_stmt_bind_param($stmtDevStock, "i", $id_libro);
            mysqli_stmt_execute($stmtDevStock);

            mysqli_commit($conexion);
            header("Location: ../views/prestamos.php");
            exit;
        } catch (Exception $e) {
            mysqli_rollback($conexion);
            header("Location: ../views/prestamos.php?error=error_sistema");
            exit;
        }
    }
}

header("Location: ../views/prestamos.php");
exit;
?>
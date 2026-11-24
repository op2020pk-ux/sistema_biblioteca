<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}
require_once '../config/conexion.php';
$esAdmin = ($_SESSION['rol'] === 'admin');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($configSistema['nombre_sistema']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3 vh-100" style="width: 260px; position: fixed;">
        <div class="text-center mb-3">
            <img src="../<?= htmlspecialchars($configSistema['imagen_sistema']) ?>" width="70" height="70" class="rounded-circle bg-white p-1 mb-2" style="object-fit: cover;">
            <h6 class="fw-bold"><?= htmlspecialchars($configSistema['nombre_sistema']) ?></h6>
            <span class="badge bg-warning text-dark text-uppercase"><?= $_SESSION['rol'] ?></span>
        </div>
        <hr>
        <ul class="nav flex-column gap-1">
            <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">📊 Dashboard</a></li>
            
            <?php if ($esAdmin): ?>
                <li class="nav-item"><a href="usuarios.php" class="nav-link text-white">👥 Usuarios</a></li>
            <?php endif; ?>

            <li class="nav-item"><a href="estudiantes.php" class="nav-link text-white">🎓 Estudiantes</a></li>
            <li class="nav-item"><a href="categorias.php" class="nav-link text-white">🏷️ Categorías</a></li>
            <li class="nav-item"><a href="libros.php" class="nav-link text-white">📚 Libros (Físicos/Digitales)</a></li>
            <li class="nav-item"><a href="prestamos.php" class="nav-link text-white">🔄 Préstamos</a></li>

            <?php if ($esAdmin): ?>
                <hr>
                <li class="nav-item"><a href="configuracion.php" class="nav-link text-info fw-bold">⚙️ Configuración Sistema</a></li>
            <?php endif; ?>

            <hr>
            <li class="nav-item"><a href="desarrollador.php" class="nav-link text-warning fw-bold">💻 Desarrollador</a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link text-danger">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>

    <!-- Contenido Principal -->
    <div class="p-4 w-100" style="margin-left: 260px;">
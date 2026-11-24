<?php
session_start();
// Autenticación automática temporal para pruebas
$_SESSION['usuario_id'] = 1;
$_SESSION['nombre']     = 'Administrador';
$_SESSION['rol']        = 'admin';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Directo | Sistema de Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-directo { border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); padding: 40px; background: white; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-directo">
                <h3 class="fw-bold mb-3">Modo de Prueba Directo</h3>
                <p class="text-muted mb-4">El inicio de sesión ha sido omitido temporalmente para verificar el funcionamiento del sistema.</p>
                <a href="views/dashboard.php" class="btn btn-primary btn-lg fw-bold w-100 py-3">Entrar al Panel de Control →</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
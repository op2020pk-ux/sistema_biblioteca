<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: views/dashboard.php");
    exit;
}
require_once 'config/conexion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | <?= htmlspecialchars($configSistema['nombre_sistema']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); height: 100vh; display: flex; align-items: center; }
        .login-card { border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        .login-img { object-fit: cover; width: 100%; height: 100%; min-height: 350px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card login-card">
                <div class="row g-0">
                    <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-3">
                        <img src="<?= htmlspecialchars($configSistema['imagen_sistema']) ?>" alt="Imagen del Sistema" class="login-img rounded">
                    </div>
                    <div class="col-md-6 p-4 d-flex flex-column justify-content-center">
                        <h4 class="text-center fw-bold mb-1"><?= htmlspecialchars($configSistema['nombre_sistema']) ?></h4>
                        <p class="text-center text-muted small mb-4">Inicie sesión para ingresar al panel</p>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger py-2 small">Usuario o contraseña incorrectos.</div>
                        <?php endif; ?>

                        <form action="controllers/auth_controller.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" name="usuario" class="form-control" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold">Ingresar</button>
                            <a href="index.php" class="btn btn-link w-100 mt-2 text-decoration-none small">← Volver a la Página Web</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php 
include '../includes/header.php'; 
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    echo "<div class='alert alert-danger'>Acceso restringido solo para Administradores.</div>";
    include '../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (nombre, usuario, password, rol) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $nombre, $usuario, $password, $rol);
    mysqli_stmt_execute($stmt);
    
    echo "<script>window.location.href='usuarios.php';</script>";
    exit;
}

$resultadoUsuarios = mysqli_query($conexion, "SELECT * FROM usuarios");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestión de Usuarios del Sistema (Admin / Staff)</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUsuario">+ Nuevo Usuario</button>
</div>

<div class="card shadow-sm p-3 bg-white">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($u = mysqli_fetch_assoc($resultadoUsuarios)): ?>
            <tr>
                <td><?= htmlspecialchars($u['nombre']) ?></td>
                <td><?= htmlspecialchars($u['usuario']) ?></td>
                <td><span class="badge bg-secondary text-uppercase"><?= $u['rol'] ?></span></td>
                <td><span class="badge bg-success">Activo</span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalUsuario" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="accion" value="crear">
            <div class="modal-header"><h5 class="modal-title">Registrar Usuario</h5></div>
            <div class="modal-body">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre completo" required>
                <input type="text" name="usuario" class="form-control mb-2" placeholder="Nombre de usuario (login)" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>
                <select name="rol" class="form-select mb-2" required>
                    <option value="staff">Staff / Bibliotecario</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
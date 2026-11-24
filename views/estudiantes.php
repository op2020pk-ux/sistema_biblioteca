<?php 
include '../includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'crear') {
        $cedula = trim($_POST['cedula']);
        $nombre = trim($_POST['nombre_completo']);
        $carrera = trim($_POST['carrera_grado']);
        $telefono = trim($_POST['telefono']);
        
        $stmt = mysqli_prepare($conexion, "INSERT INTO estudiantes (cedula, nombre_completo, carrera_grado, telefono) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $cedula, $nombre, $carrera, $telefono);
        mysqli_stmt_execute($stmt);
        
        echo "<script>window.location.href='estudiantes.php';</script>";
        exit;
    }
}

$resultadoEstudiantes = mysqli_query($conexion, "SELECT * FROM estudiantes ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestión de Estudiantes</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEstudiante">+ Nuevo Estudiante</button>
</div>

<div class="card shadow-sm p-3 bg-white">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre Completo</th>
                <th>Carrera / Grado</th>
                <th>Teléfono</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($est = mysqli_fetch_assoc($resultadoEstudiantes)): ?>
            <tr>
                <td><?= htmlspecialchars($est['cedula']) ?></td>
                <td><?= htmlspecialchars($est['nombre_completo']) ?></td>
                <td><?= htmlspecialchars($est['carrera_grado']) ?></td>
                <td><?= htmlspecialchars($est['telefono']) ?></td>
                <td>
                    <span class="badge <?= $est['estado'] == 1 ? 'bg-success' : 'bg-danger' ?>">
                        <?= $est['estado'] == 1 ? 'Activo' : 'Inactivo' ?>
                    </span>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalEstudiante" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="accion" value="crear">
            <div class="modal-header"><h5 class="modal-title">Registrar Estudiante</h5></div>
            <div class="modal-body">
                <input type="text" name="cedula" class="form-control mb-2" placeholder="Cédula / Identificación" required>
                <input type="text" name="nombre_completo" class="form-control mb-2" placeholder="Nombre completo" required>
                <input type="text" name="carrera_grado" class="form-control mb-2" placeholder="Carrera o Grado académico" required>
                <input type="text" name="telefono" class="form-control mb-2" placeholder="Teléfono de contacto">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Estudiante</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php 
include '../includes/header.php'; 

$qPrestamos = mysqli_query($conexion, "SELECT p.*, e.nombre_completo AS estudiante, l.titulo AS libro, u.nombre AS operador 
                          FROM prestamos p 
                          JOIN estudiantes e ON p.id_estudiante = e.id 
                          JOIN libros l ON p.id_libro = l.id 
                          JOIN usuarios u ON p.id_usuario = u.id 
                          ORDER BY p.id DESC");
$prestamos = [];
while ($row = mysqli_fetch_assoc($qPrestamos)) { $prestamos[] = $row; }

$qEst = mysqli_query($conexion, "SELECT * FROM estudiantes WHERE estado = 1");
$estudiantesList = [];
while ($row = mysqli_fetch_assoc($qEst)) { $estudiantesList[] = $row; }

$qLib = mysqli_query($conexion, "SELECT * FROM libros WHERE tipo = 'fisico' AND estado = 'activo' AND stock > 0");
$librosList = [];
while ($row = mysqli_fetch_assoc($qLib)) { $librosList[] = $row; }
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Control de Préstamos de Libros Físicos</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalPrestamo">+ Registrar Préstamo</button>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] === 'sin_stock'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Atención!</strong> El libro seleccionado no tiene stock disponible en este momento.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm p-3 bg-white">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Estudiante</th>
                <th>Libro Prestado</th>
                <th>F. Préstamo</th>
                <th>F. Devolución Est.</th>
                <th>Atendido por</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamos as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= htmlspecialchars($p['estudiante']) ?></td>
                <td><?= htmlspecialchars($p['libro']) ?></td>
                <td><?= $p['fecha_prestamo'] ?></td>
                <td><?= $p['fecha_devolucion_estimada'] ?></td>
                <td><?= htmlspecialchars($p['operador']) ?></td>
                <td>
                    <span class="badge <?= $p['estado'] == 'prestado' ? 'bg-warning text-dark' : 'bg-success' ?>">
                        <?= ucfirst($p['estado']) ?>
                    </span>
                </td>
                <td>
                    <?php if ($p['estado'] == 'prestado'): ?>
                    <form action="../controllers/prestamos_controller.php" method="POST">
                        <input type="hidden" name="accion" value="devolver">
                        <input type="hidden" name="id_prestamo" value="<?= $p['id'] ?>">
                        <input type="hidden" name="id_libro" value="<?= $p['id_libro'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">Marcar Devolución</button>
                    </form>
                    <?php else: ?>
                        <small class="text-muted">Devuelto el <?= $p['fecha_devolucion_real'] ?></small>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalPrestamo" tabindex="-1">
    <div class="modal-dialog">
        <form action="../controllers/prestamos_controller.php" method="POST" class="modal-content">
            <input type="hidden" name="accion" value="prestar">
            <div class="modal-header"><h5 class="modal-title">Nuevo Préstamo de Libro</h5></div>
            <div class="modal-body">
                <label class="form-label small">Estudiante:</label>
                <select name="id_estudiante" class="form-select mb-3" required>
                    <option value="">Seleccione estudiante...</option>
                    <?php foreach ($estudiantesList as $est): ?>
                        <option value="<?= $est['id'] ?>"><?= htmlspecialchars($est['nombre_completo']) ?> (C.I: <?= $est['cedula'] ?>)</option>
                    <?php endforeach; ?>
                </select>

                <label class="form-label small">Libro Físico Disponible:</label>
                <select name="id_libro" class="form-select mb-3" required>
                    <option value="">Seleccione libro...</option>
                    <?php foreach ($librosList as $lib): ?>
                        <option value="<?= $lib['id'] ?>"><?= htmlspecialchars($lib['titulo']) ?> (Disponibles: <?= $lib['stock'] ?>)</option>
                    <?php endforeach; ?>
                </select>

                <label class="form-label small">Fecha Estimada de Devolución:</label>
                <input type="date" name="fecha_devolucion_estimada" class="form-control mb-3" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
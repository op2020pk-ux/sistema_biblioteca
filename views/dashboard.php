<?php include '../includes/header.php'; 

$qFisicos   = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM libros WHERE tipo='fisico' AND estado='activo'");
$totalFisicos = mysqli_fetch_assoc($qFisicos)['total'];

$qDigitales = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM libros WHERE tipo='digital' AND estado='activo'");
$totalDigitales = mysqli_fetch_assoc($qDigitales)['total'];

$qInactivos = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM libros WHERE estado='inactivo'");
$totalInactivos = mysqli_fetch_assoc($qInactivos)['total'];

$qPrestamos = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM prestamos WHERE estado='prestado'");
$prestamosAct = mysqli_fetch_assoc($qPrestamos)['total'];

$qEstudiantes = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM estudiantes WHERE estado=1");
$estudiantes = mysqli_fetch_assoc($qEstudiantes)['total'];
?>
<h3>Panel de Control (Dashboard)</h3>
<p class="text-muted">Bienvenido, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong></p>

<div class="row g-3 mt-2">
    <div class="col-md-3">
        <div class="card text-bg-primary p-3">
            <h5>Libros Físicos</h5>
            <h2><?= $totalFisicos ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success p-3">
            <h5>Libros Digitales</h5>
            <h2><?= $totalDigitales ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning p-3">
            <h5>Préstamos Activos</h5>
            <h2><?= $prestamosAct ?></h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-secondary p-3">
            <h5>Libros Inactivos</h5>
            <h2><?= $totalInactivos ?></h2>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
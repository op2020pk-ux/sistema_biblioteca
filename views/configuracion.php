<?php include '../includes/header.php'; 
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') { 
    echo "<div class='alert alert-danger'>Solo administradores.</div>"; 
    include '../includes/footer.php';
    exit; 
}
?>
<h3>Configuración General del Sistema</h3>
<p class="text-muted">Personaliza la información institucional y la apariencia de tu biblioteca.</p>

<?php if (isset($_GET['actualizado'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>¡Excelente!</strong> Configuración actualizada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card p-4 shadow-sm bg-white" style="max-width: 700px;">
    <form action="../controllers/config_controller.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-bold">Nombre del Sistema / Biblioteca</label>
            <input type="text" name="nombre_sistema" class="form-control" value="<?= htmlspecialchars($configSistema['nombre_sistema'] ?? '') ?>" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">RIF / Identificación Fiscal</label>
                <input type="text" name="rif" class="form-control" placeholder="Ej: J-12345678-9" value="<?= htmlspecialchars($configSistema['rif'] ?? '') ?>">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Dirección de la Biblioteca</label>
            <textarea name="direccion" class="form-control" rows="2" placeholder="Indica la dirección física..."><?= htmlspecialchars($configSistema['direccion'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Logo / Imagen del Sistema</label>
            <input type="file" name="imagen_sistema" accept="image/*" class="form-control">
            <div class="mt-2">
                <small class="text-muted d-block">Imagen actual:</small>
                <?php if (!empty($configSistema['imagen_sistema'])): ?>
                    <img src="../<?= htmlspecialchars($configSistema['imagen_sistema']) ?>" width="100" class="rounded border mt-1 bg-light p-1">
                <?php else: ?>
                    <span class="text-muted small">Sin imagen asignada</span>
                <?php endif; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary fw-bold px-4">Guardar Cambios</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
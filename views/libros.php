<?php include '../includes/header.php'; 

$qCategorias = mysqli_query($conexion, "SELECT * FROM categorias");
$categoriasList = [];
while ($cat = mysqli_fetch_assoc($qCategorias)) {
    $categoriasList[] = $cat;
}

$qFisicos = mysqli_query($conexion, "SELECT l.*, c.nombre AS cat FROM libros l JOIN categorias c ON l.id_categoria=c.id WHERE l.tipo='fisico' AND l.estado='activo'");
$fisicos = [];
while ($row = mysqli_fetch_assoc($qFisicos)) { $fisicos[] = $row; }

$qDigitales = mysqli_query($conexion, "SELECT l.*, c.nombre AS cat FROM libros l JOIN categorias c ON l.id_categoria=c.id WHERE l.tipo='digital' AND l.estado='activo'");
$digitales = [];
while ($row = mysqli_fetch_assoc($qDigitales)) { $digitales[] = $row; }

$qInactivos = mysqli_query($conexion, "SELECT l.*, c.nombre AS cat FROM libros l JOIN categorias c ON l.id_categoria=c.id WHERE l.estado='inactivo'");
$inactivos = [];
while ($row = mysqli_fetch_assoc($qInactivos)) { $inactivos[] = $row; }
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestión de Libros</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLibro">+ Nuevo Libro</button>
</div>

<ul class="nav nav-tabs mb-3" id="librosTab" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#fisicos">📗 Libros Físicos (<?= count($fisicos) ?>)</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#digitales">📘 Libros Digitales (<?= count($digitales) ?>)</button></li>
    <li class="nav-item"><button class="nav-link text-danger" data-bs-toggle="tab" data-bs-target="#inactivos">📕 Libros Inactivos (<?= count($inactivos) ?>)</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="fisicos">
        <table class="table table-bordered bg-white">
            <thead><tr><th>Código</th><th>Título</th><th>Autor</th><th>Categoría</th><th>Stock</th><th>Acción</th></tr></thead>
            <tbody>
                <?php foreach ($fisicos as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['codigo']) ?></td><td><?= htmlspecialchars($l['titulo']) ?></td><td><?= htmlspecialchars($l['autor']) ?></td><td><?= htmlspecialchars($l['cat']) ?></td>
                    <td><span class="badge bg-primary"><?= $l['stock'] ?></span></td>
                    <td>
                        <form action="../controllers/libros_controller.php" method="POST">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="id" value="<?= $l['id'] ?>">
                            <input type="hidden" name="estado" value="inactivo">
                            <button class="btn btn-sm btn-outline-danger">Desactivar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="digitales">
        <table class="table table-bordered bg-white">
            <thead><tr><th>Código</th><th>Título</th><th>Autor</th><th>Categoría</th><th>Archivo</th><th>Acción</th></tr></thead>
            <tbody>
                <?php foreach ($digitales as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['codigo']) ?></td><td><?= htmlspecialchars($l['titulo']) ?></td><td><?= htmlspecialchars($l['autor']) ?></td><td><?= htmlspecialchars($l['cat']) ?></td>
                    <td><a href="../<?= htmlspecialchars($l['archivo_pdf']) ?>" target="_blank" class="btn btn-sm btn-success">Ver PDF</a></td>
                    <td>
                        <form action="../controllers/libros_controller.php" method="POST">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="id" value="<?= $l['id'] ?>">
                            <input type="hidden" name="estado" value="inactivo">
                            <button class="btn btn-sm btn-outline-danger">Desactivar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="tab-pane fade" id="inactivos">
        <table class="table table-bordered bg-white">
            <thead><tr><th>Código</th><th>Título</th><th>Tipo</th><th>Categoría</th><th>Acción</th></tr></thead>
            <tbody>
                <?php foreach ($inactivos as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['codigo']) ?></td><td><?= htmlspecialchars($l['titulo']) ?></td><td><?= strtoupper($l['tipo']) ?></td><td><?= htmlspecialchars($l['cat']) ?></td>
                    <td>
                        <form action="../controllers/libros_controller.php" method="POST">
                            <input type="hidden" name="accion" value="cambiar_estado">
                            <input type="hidden" name="id" value="<?= $l['id'] ?>">
                            <input type="hidden" name="estado" value="activo">
                            <button class="btn btn-sm btn-success">Reactivar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalLibro" tabindex="-1">
    <div class="modal-dialog">
        <form action="../controllers/libros_controller.php" method="POST" enctype="multipart/form-data" class="modal-content">
            <input type="hidden" name="accion" value="crear">
            <div class="modal-header"><h5 class="modal-title">Registrar Libro</h5></div>
            <div class="modal-body">
                <input type="text" name="codigo" class="form-control mb-2" placeholder="Código (Ej: LIB-001)" required>
                <input type="text" name="titulo" class="form-control mb-2" placeholder="Título del libro" required>
                <input type="text" name="autor" class="form-control mb-2" placeholder="Autor" required>
                <select name="id_categoria" class="form-select mb-2" required>
                    <?php foreach ($categoriasList as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="tipo" id="tipoLibro" class="form-select mb-2" onchange="toggleTipo()" required>
                    <option value="fisico">Libro Físico</option>
                    <option value="digital">Libro Digital (PDF)</option>
                </select>
                <div id="campoStock">
                    <input type="number" name="stock" class="form-control mb-2" placeholder="Cantidad disponible (Stock)" value="1" min="1">
                </div>
                <div id="campoPdf" style="display:none;">
                    <label class="small text-muted">Subir archivo PDF:</label>
                    <input type="file" name="archivo_pdf" accept="application/pdf" class="form-control mb-2">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Libro</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleTipo() {
    const tipo = document.getElementById('tipoLibro').value;
    document.getElementById('campoStock').style.display = (tipo === 'fisico') ? 'block' : 'none';
    document.getElementById('campoPdf').style.display   = (tipo === 'digital') ? 'block' : 'none';
}
</script>
<?php include '../includes/footer.php'; ?>
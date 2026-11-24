<?php 
include '../includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $nombre = trim($_POST['nombre']);
    $desc = trim($_POST['descripcion']);
    
    $stmt = mysqli_prepare($conexion, "INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $nombre, $desc);
    mysqli_stmt_execute($stmt);
    
    echo "<script>window.location.href='categorias.php';</script>";
    exit;
}

$resultadoCategorias = mysqli_query($conexion, "SELECT * FROM categorias ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestión de Categorías</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">+ Nueva Categoría</button>
</div>

<div class="card shadow-sm p-3 bg-white">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de la Categoría</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cat = mysqli_fetch_assoc($resultadoCategorias)): ?>
            <tr>
                <td><?= $cat['id'] ?></td>
                <td><strong><?= htmlspecialchars($cat['nombre']) ?></strong></td>
                <td><?= htmlspecialchars($cat['descripcion']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="accion" value="crear">
            <div class="modal-header"><h5 class="modal-title">Nueva Categoría</h5></div>
            <div class="modal-body">
                <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre de categoría" required>
                <textarea name="descripcion" class="form-control mb-2" placeholder="Breve descripción..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Categoría</button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

@session_start();

$idprestamo = $_GET['idprestamo'] ?? 0;
$idusuario_sesion = $_SESSION['idusuario'] ?? 0;

// Obtener datos del préstamo
$prestamoData = CRUD("SELECT p.*, l.titulo, d.nombres, d.apellidos 
                      FROM prestamos p
                      JOIN libros l ON p.idlibro = l.idlibro
                      JOIN docentes d ON p.idusuario = d.idusuario
                      WHERE p.idprestamo='$idprestamo' AND p.idusuario='$idusuario_sesion' AND p.estado='1'", "s");

if (!is_array($prestamoData) || count($prestamoData) === 0) {
    echo "<div class='alert alert-danger'>No tiene permisos para editar este préstamo</div>";
    return;
}

$prestamo = $prestamoData[0];
?>

<input type="hidden" name="idprestamo" value="<?php echo $idprestamo; ?>">

<div class="alert alert-info">
    <strong>Libro:</strong> <?php echo $prestamo['titulo']; ?><br>
    <strong>Docente:</strong> <?php echo $prestamo['nombres'] . ' ' . $prestamo['apellidos']; ?>
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Fecha Préstamo:</b>
    </span>
    <input type="date" class="form-control" value="<?php echo $prestamo['fechaprestamo']; ?>" readonly>
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Cantidad:</b>
    </span>
    <input type="number" class="form-control" value="<?php echo $prestamo['cantidad'] ?? 1; ?>" readonly>
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Nueva Fecha Devolución:</b>
    </span>
    <input type="date" class="form-control" name="fecharetorno" value="<?php echo $prestamo['fecharetorno']; ?>" min="<?php echo date('Y-m-d'); ?>">
</div>

<p class="text-muted"><small>Solo puede modificar la fecha de devolución de sus préstamos activos.</small></p><?php

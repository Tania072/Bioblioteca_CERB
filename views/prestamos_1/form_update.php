<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idprestamo = $_GET['idprestamo'] ?? 0;

// Obtener datos del préstamo
$prestamoData = CRUD("SELECT p.*, l.titulo, CONCAT(d.nombres, ' ', d.apellidos) as docente_nombre
                      FROM prestamos p
                      JOIN libros l ON p.idlibro = l.idlibro
                      JOIN docentes d ON p.idusuario = d.idusuario
                      WHERE p.idprestamo='$idprestamo'", "s");

if (!is_array($prestamoData) || count($prestamoData) === 0) {
    echo "<div class='alert alert-danger'>Préstamo no encontrado</div>";
    return;
}

$prestamo = $prestamoData[0];
?>

<input type="hidden" name="idprestamo" value="<?php echo $idprestamo; ?>">

<div class="alert alert-info">
    <strong>Libro:</strong> <?php echo $prestamo['titulo']; ?><br>
    <strong>Docente:</strong> <?php echo $prestamo['docente_nombre']; ?>
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Cantidad:</b>
    </span>
    <input type="number" class="form-control" name="cantidad" min="1" value="<?php echo $prestamo['cantidad'] ?? 1; ?>">
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Fecha Préstamo:</b>
    </span>
    <input type="date" class="form-control" name="fechaprestamo" value="<?php echo $prestamo['fechaprestamo']; ?>">
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Fecha Devolución:</b>
    </span>
    <input type="date" class="form-control" name="fecharetorno" value="<?php echo $prestamo['fecharetorno']; ?>">
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Estado:</b>
    </span>
    <select class="form-select" name="estado">
        <option value="1" <?php echo ($prestamo['estado'] == 1) ? 'selected' : ''; ?>>En Préstamo</option>
        <option value="0" <?php echo ($prestamo['estado'] == 0) ? 'selected' : ''; ?>>Finalizado</option>
    </select>
</div>
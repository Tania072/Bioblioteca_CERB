<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

@session_start();

$idlibro = $_GET['idlibro'] ?? 0;
$iddocente = $_GET['iddocente'] ?? 0;
$idusuario_sesion = $_SESSION['idusuario'] ?? 0;

// Obtener información del libro
$libroData = CRUD("SELECT * FROM libros WHERE idlibro='$idlibro'", "s");
$libro = $libroData[0] ?? null;

// Obtener información del docente
$docenteData = CRUD("SELECT d.iddocente, d.nombres, d.apellidos 
                     FROM docentes d 
                     WHERE d.iddocente='$iddocente'", "s");
$docente = $docenteData[0] ?? null;

if (!$libro || !$docente) {
    echo "<div class='alert alert-danger'>Error: No se pudo cargar la información necesaria.</div>";
    return;
}

// Verificar si existe el campo ejemplares_disponibles, si no usar ejemplares
$ejemplares_disponibles = isset($libro['ejemplares_disponibles']) ? $libro['ejemplares_disponibles'] : $libro['ejemplares'];
$fecha_actual = date("Y-m-d");
$fecha_devolucion = date("Y-m-d", strtotime("+7 day"));
?>

<input type="hidden" name="idlibro" value="<?php echo $idlibro; ?>">
<input type="hidden" name="iddocente" value="<?php echo $iddocente; ?>">

<div class="alert alert-info">
    <strong>Libro:</strong> <?php echo $libro['titulo']; ?><br>
    <strong>Autor:</strong> <?php echo $libro['autor']; ?><br>
    <strong>Ejemplares disponibles:</strong> <span class="badge bg-primary"><?php echo $ejemplares_disponibles; ?></span>
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Docente:</b>
    </span>
    <input type="text" class="form-control" value="<?php echo $docente['nombres'] . ' ' . $docente['apellidos']; ?>" readonly>
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Cantidad a prestar:</b>
    </span>
    <input type="number" class="form-control" name="cantidad" min="1" max="<?php echo $ejemplares_disponibles; ?>" value="1">
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Fecha Préstamo:</b>
    </span>
    <input type="date" class="form-control" name="fechaprestamo" value="<?php echo $fecha_actual; ?>" readonly>
</div>

<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Fecha Devolución:</b>
    </span>
    <input type="date" class="form-control" name="fecharetorno" value="<?php echo $fecha_devolucion; ?>" min="<?php echo $fecha_actual; ?>">
</div>
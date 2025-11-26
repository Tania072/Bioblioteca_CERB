<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
@session_start();

$idprestamo = $_GET['idprestamo'] ?? null;

if ($idprestamo === null) {
    echo '<div class="alert alert-danger">No se ha especificado el préstamo.</div>';
    exit;
}

$dataPrestamo = CRUD("SELECT * FROM prestamos WHERE idprestamo='$idprestamo'", "S") ?? [];

if (!$dataPrestamo) {
    echo '<div class="alert alert-danger">No se encontró la información del préstamo.</div>';
    exit;
}

$prestamo = $dataPrestamo[0];

$idusuario_prestamo = $prestamo['idusuario'];
$idlibro_prestamo   = $prestamo['idlibro'];
$fechaprestamo      = $prestamo['fechaprestamo'];
$fecharetorno       = $prestamo['fecharetorno'];
$estado             = $prestamo['estado'];

$DataIdUbicacion = CRUD("SELECT idubicacion FROM libros WHERE idlibro='$idlibro_prestamo'","s");

foreach($DataIdUbicacion AS $result){
    $idubicacion = $result['idubicacion'];
}
$DataUbicacion = CRUD("SELECT nombre, descripcion FROM ubicaciones WHERE idubicacion='$idubicacion'","s");
foreach($DataUbicacion AS $result){
    $ubicacion = $result['nombre'];
    $descripcion = $result['descripcion'];
}
// Sesión
$idrol_sesion     = $_SESSION['idrol']     ?? null;
$idusuario_sesion = $_SESSION['idusuario'] ?? null;

// Listas
$docentes = CRUD("SELECT * FROM docentes WHERE estado = 1 ORDER BY nombres, apellidos", "S") ?? [];
$libros   = CRUD("SELECT * FROM libros ORDER BY titulo", "S") ?? [];

// Normalizar fechas para el input type="date"
if (!empty($fechaprestamo)) {
    $fechaprestamo = date('Y-m-d', strtotime($fechaprestamo));
}
if (!empty($fecharetorno) && $fecharetorno != '0000-00-00') {
    $fecharetorno = date('Y-m-d', strtotime($fecharetorno));
} else {
    $fecharetorno = '';
}
?>
<input type="hidden" name="idprestamo" value="<?php echo $idprestamo; ?>">

<div class="input-group mb-3">
    <span class="input-group-text"><b>Docente:</b></span>
    <?php if ($idrol_sesion == 2 && $idusuario_sesion): ?>
        <?php
        $nombreDocente = buscavalor(
            'docentes',
            "CONCAT(nombres,' ',apellidos)",
            "idusuario='" . $idusuario_prestamo . "'"
        );
        ?>
        <input type="text"
            class="form-control"
            value="<?php echo htmlspecialchars($nombreDocente, ENT_QUOTES, 'UTF-8'); ?>"
            disabled>
        <input type="hidden" name="idusuario" value="<?php echo $idusuario_prestamo; ?>">
    <?php else: ?>
        <select class="form-select" name="idusuario" required>
            <option value="">Seleccione un docente</option>
            <?php foreach ($docentes as $doc): ?>
                <option value="<?php echo $doc['idusuario']; ?>"
                    <?php echo ($doc['idusuario'] == $idusuario_prestamo) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($doc['nombres'] . ' ' . $doc['apellidos'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</div>

<div class="input-group mb-3">
    <span class="input-group-text"><b>Libro:</b></span>
    <select class="form-select" name="idlibro" required>
        <option value="">Seleccione un libro</option>
        <?php foreach ($libros as $lib): ?>
            <option value="<?php echo $lib['idlibro']; ?>"
                <?php echo ($lib['idlibro'] == $idlibro_prestamo) ? 'selected' : ''; ?>>
                <?php
                $texto = $lib['titulo'];
                if (!empty($lib['isbn'])) {
                    $texto .= ' (ISBN: ' . $lib['isbn'] . ')';
                }
                echo htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
                ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<div class="input-group mb-3">
    <span class="input-group-text"><b>Ubicación</b></span>
    <textarea class="form-control" readonly><?= '('.$ubicacion.') '.$descripcion; ?></textarea>
</div>
<div class="input-group mb-3">
    <span class="input-group-text"><b>Fecha préstamo:</b></span>
    <input type="date"
        class="form-control"
        name="fechaprestamo"
        value="<?php echo $fechaprestamo; ?>"
        required>
</div>

<div class="input-group mb-3">
    <span class="input-group-text"><b>Fecha retorno:</b></span>
    <input type="date"
        class="form-control"
        name="fecharetorno"
        value="<?php echo $fecharetorno; ?>">
</div>

<div class="input-group mb-3">
    <label class="input-group-text" for="estado"><b>Estado:</b></label>
    <select class="form-select" id="estado" name="estado">
        <option value="1" <?php echo ($estado == 1) ? 'selected' : ''; ?>>En Préstamo</option>
        <option value="2" <?php echo ($estado == 2) ? 'selected' : ''; ?>>Finalizado</option>
    </select>
</div>
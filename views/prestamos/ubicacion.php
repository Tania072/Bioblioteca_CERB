<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idlibro = $_GET['idlibro'];

$DataIdUbicacion = CRUD("SELECT idubicacion,ejemplares FROM libros WHERE idlibro='$idlibro'", "s") ?? [];

$idubicacion = $DataIdUbicacion[0]['idubicacion'] ?? '';
$ejemplares = $DataIdUbicacion[0]['ejemplares'] ?? '';
$DataUbicacion = CRUD("SELECT nombre, descripcion FROM ubicaciones WHERE idubicacion='$idubicacion'", "s")?? [];
$ubicacion = $DataUbicacion[0]['nombre'] ?? '';
$descripcion = $DataUbicacion[0]['descripcion'] ?? '';
?>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Cantidad a prestar:</b>
    </span>
    <input type="number" class="form-control" name="cantidad" min="1" max="<?php echo $ejemplares; ?>" value="1">
</div>
<div class="input-group mb-3">
    <span class="input-group-text"><b>Ubicación</b></span>
    <textarea class="form-control" readonly><?= ($idubicacion != 0)? '(' . $ubicacion . ') ' . $descripcion:''; ?></textarea>
</div>
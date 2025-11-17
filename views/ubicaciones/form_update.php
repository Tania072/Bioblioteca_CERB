<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idubicacion = $_GET['idubicacion'];

$dataUbicaciones = CRUD("SELECT * FROM ubicaciones WHERE idubicacion='$idubicacion'", "s") ?? [];

foreach ($dataUbicaciones as $result) {
    $nombre = $result['nombre'];
    $descripcion = $result['descripcion'];
    $estado = $result['estado'];
}
?>
<input type="text" name="idubicacion" value="<?= $idubicacion; ?>" hidden>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Nombre:</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingresar nombre" name="nombre" value="<?php echo $nombre; ?>">
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Descripción:</b>
    </span>
    <textarea class="form-control" placeholder="Ingrese descripción" name="descripcion" rows="3"><?php echo $descripcion; ?></textarea>
</div>
<div class="input-group mb-3">
    <label class="input-group-text" for="inputGroupSelect01">
        <b>Estado:</b>
    </label>
    <select class="form-select" id="estado" name="estado">
        <option value="1" <?= ($estado == 1) ? 'selected' : '' ?>>Activo</option>
        <option value="0" <?= ($estado == 0) ? 'selected' : '' ?>>Inactivo</option>
    </select>
</div>
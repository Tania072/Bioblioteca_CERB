<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idgenero = $_GET['idgenero'];

$datagenero = CRUD("SELECT * FROM generos WHERE idgenero='$idgenero'", "s");

foreach ($datagenero as $result) {
    $genero = $result['genero'];
    $descripcion = $result['descripcion'];
}
?>
<input type="text" name="idgenero" value="<?= $idgenero; ?>" hidden>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Genero</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese Genero" name="genero" value="<?php echo $genero; ?>">
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Descripcion:</b>
    </span>
    <textarea class="form-control" placeholder="Ingrese Descripcion" name="descripcion"><?php echo $descripcion; ?></textarea>
</div>
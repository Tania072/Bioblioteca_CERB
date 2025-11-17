<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$datagenero = CRUD("SELECT * FROM generos", "s");
$dataubicacion = CRUD("SELECT * FROM ubicaciones", "s");

?>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>ISBN:</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingresar Isbn" name="isbn">
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Titulo</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese Libro" name="titulo">
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Autor:</b>
    </span>
    <textarea class="form-control" placeholder="Ingrese Autor" name="autor"></textarea>
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Ejemplares</b>
    </span>
    <input type="number" placeholder="Ingrese Numero de Ejemplares" name="ejemplares" class="form-control" min="1" max="100">
</div>

<div class="input-group mb-3">
    <span class="input-group-text" for="inputGroupSelect01">
        <b>Ubicación:</b>
    </span>
    <select class="form-select" id="idubicacion" name="idubicacion">
        <option value="0" disabled selected>Seleccione Ubicación</option>
        <?php foreach ($dataubicacion as $result): ?>
            <option value="<?php echo $result['idubicacion']; ?>">
                <?php echo $result['nombre']; ?>
            </option>
        <?php endforeach ?>
    </select>
</div>

<div class="input-group mb-3">
    <span class="input-group-text" for="inputGroupSelect01">
        <b>Genero:</b>
    </span>
    <select class="form-select" id="idgenero" name="idgenero">
        <option value="0" disabled selected>Seleccione Tipo</option>
        <?php foreach ($datagenero as $result): ?>
            <option value="<?php echo $result['idgenero']; ?>">
                <?php echo $result['genero']; ?>
            </option>
        <?php endforeach ?>
    </select>
</div>
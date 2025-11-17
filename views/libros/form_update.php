<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idlibro = $_GET['idlibro'];

$dataLibros = CRUD("SELECT * FROM libros WHERE idlibro='$idlibro'", "s") ?? [];
$datagenero = CRUD("SELECT * FROM generos", "s");
$dataubicacion = CRUD("SELECT * FROM ubicaciones", "s");


foreach ($dataLibros as $result) {
    $isbn = $result['isbn'];
    $titulo = $result['titulo'];
    $autor = $result['autor'];
    $ejemplares = $result['ejemplares'];
    $idgenero = $result['idgenero'];
    $estado = $result['estado'];
}
?>
<input type="text" name="idlibro" value="<?= $idlibro; ?>" hidden>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>ISBN:</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingresar Isbn" name="isbn" value="<?php echo $isbn; ?>">
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Titulo</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese Libro" name="titulo" value="<?php echo $titulo; ?>">
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Autor:</b>
    </span>
    <textarea class="form-control" placeholder="Ingrese Autor" name="autor"><?php echo $autor; ?></textarea>
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Ejemplares</b>
    </span>
    <input type="number" placeholder="Ingrese Numero de Ejemplares" name="ejemplares" value="<?php echo $ejemplares; ?>" class="form-control" min="1" max="100"/>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" for="inputGroupSelect01">
        <b>Genero:</b>
    </span>
    <select class="form-select" id="idgenero" name="idgenero">
        <option value="0" disabled selected>Seleccione Tipo</option>
        <?php foreach($datagenero AS $res): ?>
            <option value="<?php echo $res['idgenero']; ?>">
                <?php echo $res['genero']; ?>
            </option>
        <?php endforeach ?>
    </select>
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
    <label class="input-group-text" for="inputGroupSelect01">
        <b>Estado:</b>
    </label>
    <select class="form-select" id="estado" name="estado">
        <option value="1" <?= ($estado == 1) ? 'selected' : '' ?>>Activo</option>
        <option value="2" <?= ($estado == 2) ? 'selected' : '' ?>>Inactivo</option>
    </select>
</div>
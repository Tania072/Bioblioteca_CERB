<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$dataPrestamos = CRUD("SELECT * FROM prestamos", "s");
$dataDocentes = CRUD("SELECT idusuario, usuario FROM usuarios WHERE idrol = 2", "s");
$dataLibros = CRUD("SELECT idlibro, titulo FROM libros", "s");

?>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Docente:</b>
    </span>
    <select class="form-select" id="idusuario" name="idusuario">
        <option value="0" disabled selected>Seleccione Docente</option>
        <?php foreach ($dataDocentes as $result): ?>
            <option value="<?php echo $result['idusuario']; ?>">
                <?php echo $result['usuario']; ?>
            </option>
        <?php endforeach ?>
    </select>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Libro:</b>
    </span>
    <select class="form-select" id="idlibro" name="idlibro">
        <option value="0" disabled selected>Seleccione Libro</option>
        <?php foreach ($dataLibros as $result): ?>
            <option value="<?php echo $result['idlibro']; ?>">
                <?php echo $result['titulo']; ?>
            </option>
        <?php endforeach ?>
    </select>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Fecha Prestamo:</b>
    </span>
    <input type="date" class="form-control" placeholder="" name="fechaprestamo" value="<?php echo date("Y-m-d"); ?>">
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Fecha Devolución:</b>
    </span>
    <input type="date" class="form-control" placeholder="" name="fecharetorno" value="<?php echo date("Y-m-d", strtotime("+7 day")); ?>">
</div>
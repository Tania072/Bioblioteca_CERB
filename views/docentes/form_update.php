<?php

include_once '../../models/conexion.php';

include_once '../../models/funciones.php';

include_once '../../controllers/funciones.php';

$iddocente = $_GET['iddocente'];

$dataDocente =  CRUD("SELECT * FROM docentes WHERE iddocente='$iddocente'", "s") ?? [];

$nombres = $dataDocente[0]['nombres'] ?? null;
$apellidos = $dataDocente[0]['apellidos'] ?? null;
$email = $dataDocente[0]['email'] ?? null;
$idusuario = $dataDocente[0]['idrol'] ?? null;
$dui = $dataDocente[0]['dui'] ?? null;
$estado = $dataDocente[0]['estado'] ?? null;

?>
<input type="text" name="iddocente" value="<?= $iddocente; ?>" hidden>
<input type="text" name="idusuario" value="<?= $idusuario; ?>" hidden>

<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1"><b>Nombres:</b></span>
    <input type="text" class="form-control" name="nombre" value="<?= $nombres; ?>" required>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1"><b>Apellidos:</b></span>
    <input type="text" class="form-control" name="apellido" value="<?= $apellidos; ?>" required>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1"><b>DUI:</b></span>
    <input type="text" class="form-control" id="dui" name="dui" value="<?= $dui; ?>" required>
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Email:</b>
    </span>
    <textarea class="form-control" name="email" required><?= $email; ?></textarea>
</div>

<div class="input-group mb-3">
    <label class="input-group-text" for="inputGroupSelect01">
        <b>Estado: </b>
    </label>
    <select class="form-select" id="estado" name="estado">
        <option value="1" <?= ($estado == 1) ? 'selected' : ''; ?>>Activo</option>
        <option value="0" <?php echo ($estado == 0) ? 'selected' : ''; ?>>Inactivo</option>
    </select>
</div>

<script>

    Inputmask("99999999-9").mask(document.getElementById("dui"));
</script>
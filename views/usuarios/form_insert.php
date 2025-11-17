<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$dataRol = CRUD("SELECT * FROM roles", "s");

?>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1"><b>Nombres:</b></span>
    <input type="text" class="form-control" name="nombre" required>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1"><b>Apellidos:</b></span>
    <input type="text" class="form-control" name="apellido" required>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Usuario</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario">
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Clave:</b>
    </span>
    <input type="password" class="form-control" placeholder="Ingresar Contraseña" name="passw">
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Email:</b>
    </span>
    <textarea class="form-control" placeholder="Ingrese Email" name="email"></textarea>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" for="inputGroupSelect01">
        <b>Tipo Usuario:</b>
    </span>
    <select class="form-select" id="idrol" name="idrol">
        <option value="0" disabled selected>Seleccione Tipo</option>
        <?php foreach ($dataRol as $result): ?>
            <option value="<?php echo $result['idrol']; ?>">
                <?php echo $result['rol']; ?>
            </option>
        <?php endforeach ?>
    </select>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1"><b>DUI:</b></span>
    <input type="text" class="form-control" id="dui" name="dui" required>
</div>
<script>
    Inputmask("99999999-9").mask(document.getElementById("dui"));
    Inputmask("9999-9999").mask(document.getElementById("telefono"));
</script>
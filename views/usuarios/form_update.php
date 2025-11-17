<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_GET['idusuario'];

$dataUsuarios = CRUD("SELECT * FROM usuarios WHERE idusuario='$idusuario'", "s") ?? [];

foreach ($dataUsuarios as $result) {
    $usuario = $result['usuario'];
    $email = $result['email'];
    $idrol = $result['idrol'];
    $estado = $result['estado'];
}

?>
<input type="text" name="idusuario" value="<?= $idusuario; ?>" hidden>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Usuario</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario" value="<?php echo $usuario; ?>">
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
    <textarea class="form-control" placeholder="Ingrese Email" name="email"><?php echo $email; ?></textarea>
</div>
<div class="input-group mb-3">
    <label class="input-group-text" for="inputGroupSelect01">
        <b>Tipo Usuario:</b>
    </label>
    <select class="form-select" id="idrol" name="idrol">
        <option value="1" <?= ($idrol == 1) ? 'selected' : '' ?>>Administrador</option>
        <option value="2" <?= ($idrol == 2) ? 'selected' : '' ?>>Docente</option>
    </select>
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
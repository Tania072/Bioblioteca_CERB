<?php
@session_start();
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_SESSION['idusuario'];

$dataUsuarios = CRUD("SELECT * FROM usuarios WHERE idusuario='$idusuario'", "s") ?? [];

$usuario = $dataUsuarios[0]['usuario']?? null;
$email = $dataUsuarios[0]['email'] ?? null;


?>
<input type="text" name="idusuario" value="<?= $idusuario; ?>" hidden>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Usuario</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario" value="<?php echo $usuario; ?>" readonly>
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


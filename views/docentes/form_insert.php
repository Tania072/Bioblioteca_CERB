<?php 
include_once '../../models/conexion.php';

include_once '../../models/funciones.php';

include_once '../../controllers/funciones.php';
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
    <span class="input-group-text" id="basic-addon1"><b>DUI:</b></span>
    <input type="text" class="form-control" id="dui" name="dui" required>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Usuario:</b>
    </span>
    <input type="text" class="form-control" placeholder="Ingrese Usuario" name="usuario" id="usuario" readonly>
</div>
<div class="input-group mb-3">
    <span class="input-group-text" id="basic-addon1">
        <b>Clave:</b>
    </span>
    <input type="password" class="form-control" placeholder="Ingrese Contraseña" name="passw" id="clave" readonly>
</div>
<div class="input-group mb-3">
    <span class="input-group-text">
        <b>Email:</b>
    </span>
    <textarea class="form-control" name="email" required></textarea>
</div>
<script>
    $(document).ready(function() {
        
        
        $("#dui").on("keyup change", function() {
            let dui = $("#dui").val();

            // Eliminar los guiones de la cadena del campo 'dui'
            let usuario = dui.replace(/-/g, "");

            // Asignar el valor sin guiones al campo 'usuario'
            $("#usuario").val(usuario);
            $("#clave").val(usuario);

            return false; 
        });
    });
    Inputmask("99999999-9").mask(document.getElementById("dui"));
        Inputmask("9999-9999").mask(document.getElementById("telefono"));
</script>
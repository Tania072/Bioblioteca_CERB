<?php
    
?>
<div class="card">
    <div class="card-header">
        <b>Bienvenido/a: <?php echo $_SESSION['usuario']; ?>
            (
            <?php
            $idrol = $_SESSION['idrol'];
            $rol_usuario = match ($idrol) {
                1 => 'Administrador',
                2 => 'Docente',
                default => 'Desconocido'
            };
            echo $rol_usuario;
            ?>
            )
        </b>
    </div>
    <div class="card-body" id="contenido-principal">
        <div style="vertical-align: middle;text-align: center;">
            <h3>Bienvenido al Sistema de Gestión Global de Archivos (SGGA)</h3>
            <img src="./public/img/Brasil.jpg" alt="Logo SGGA" class="img-fluid" width="250px">
            <hr>
            <?php
            if ($idrol == 1) {
                include 'administrador.php';
            } else {
                include 'docente.php';
            }
            ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Cerrar sesión
        $("#off").click(function() {
            var valor = $(this).attr("valor");

            alertify.confirm('labels changed!').set('labels', {
                ok: 'Aceptar',
                cancel: 'Cancelar'
            });

            alertify.confirm('Cerrar Sesión', 'Seguro/a de cerrar sesión.....', function() {
                $("#toor").load("index.php?off=" + valor);
            }, function() {
                alertify.error('Proceso cancelado');
            });
            return false;
        });

        //Acceso a panel de usuarios
        $(".panel-usuarios").click(function(){
            $("#contenido-principal").load("./views/usuarios/principal.php");
            return false; // Evita el comportamiento por defecto del enlace
        });

        //Acceso a panel de docentes
        $(".panel-docentes").click(function(){
            $("#contenido-principal").load("./views/docentes/principal.php");
            return false;
        });

        //Acceso a panel de libros
        $(".panel-libros").click(function(){
            $("#contenido-principal").load("./views/libros/principal.php");
            return false; // Evita el comportamiento por defecto del enlace
        });

        //Acceso a panel de generos
        $(".panel-generos").click(function(){
            $("#contenido-principal").load("./views/generos/principal.php");
            return false;
        });

        //Acceso a panel de prestamos
        $(".panel-prestamos").click(function(){
            $("#contenido-principal").load("./views/prestamos/principal.php");
            return false;
        });

        // Acceso a Panel de Ubicaciones
        $(".panel-ubicaciones").click(function() {
            $("#contenido-principal").load("./views/ubicaciones/principal.php");
            return false;
        });
        //Acceso a panel Manuales
        $(".panel-manuales").click(function(){
            $("#contenido-principal").load("./views/Manuales/principal.php");
            return false;
        });
        //Acceso a panel de perfil
        $('.cambio-clave').click(function(){
            $("#ModalEDU").modal('show');
            $("#ModalDDU").load("./views/perfil/form_update.php");
            return false;
        });
    });
</script>
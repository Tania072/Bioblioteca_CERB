<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_POST['idusuario'];
$idlibro = $_POST['idlibro'];
$fechaprestamo = $_POST['fechaprestamo'];
$fechadevolcion = $_POST['fecharetorno'];

$insert = CRUD("SELECT INTO prestamos(idusuario, idlibro, fechaprestamo, fecharetorno) VALUES ('$idusuario', '$idlibro', '$fechaprestamo', '$fechadevolucion')", "i");

?>
<?php if ($insert): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Prestamo Registrado");
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Prestamo No Registrado");
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php endif ?>
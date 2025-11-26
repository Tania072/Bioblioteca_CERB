<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idusuario = $_POST['idusuario'];
$idlibro = $_POST['idlibro'];
$fechaprestamo = $_POST['fechaprestamo'];
$fecharetorno = $_POST['fecharetorno'];

$insert = CRUD("INSERT INTO prestamos(idusuario, idlibro, fechaprestamo, fecharetorno, estado) VALUES ('$idusuario', '$idlibro', '$fechaprestamo', '$fecharetorno', '1')", "i");

?>
<?php if ($insert): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Préstamo Registrado");
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error: Préstamo No Registrado");
            $("#contenido-principal").load("./views/prestamos/principal.php");
        });
    </script>
<?php endif ?>
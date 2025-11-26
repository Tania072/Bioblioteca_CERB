<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idprestamo = $_POST['idprestamo'] ?? 0;
$cantidad = $_POST['cantidad'] ?? 1;
$fechaprestamo = $_POST['fechaprestamo'] ?? '';
$fecharetorno = $_POST['fecharetorno'] ?? '';
$estado = $_POST['estado'] ?? 1;

// Actualizar el préstamo
$update = CRUD("UPDATE prestamos SET cantidad='$cantidad', fechaprestamo='$fechaprestamo', fecharetorno='$fecharetorno', estado='$estado' 
               WHERE idprestamo='$idprestamo'", "u");

if ($update) {
    echo "<script>
        $(document).ready(function() {
            alertify.success('Préstamo actualizado correctamente');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
} else {
    echo "<script>
        $(document).ready(function() {
            alertify.error('Error al actualizar el préstamo');
            $('#contenido-principal').load('./views/prestamos/principal.php');
        });
    </script>";
}
?>
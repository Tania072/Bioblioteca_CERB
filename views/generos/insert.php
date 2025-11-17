<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$genero = $_POST['genero'];
$descripcion = $_POST['descripcion'];

$insert = CRUD("INSERT INTO generos(genero, descripcion) VALUES ('$genero', '$descripcion')", "i");
?>
<?php if ($insert): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Genero Registrado");
            $("#contenido-principal").load("./views/generos/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Genero No Registrado");
            $("#contenido-principal").load("./views/generos/principal.php");
        });
    </script>
<?php endif ?>
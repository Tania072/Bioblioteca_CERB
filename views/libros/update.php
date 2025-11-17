<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$idlibro = $_POST['idlibro'];
$isbn = $_POST['isbn'];
$titulo = $_POST['titulo'];
$autor = $_POST['autor'];
$ejemplares = $_POST['ejemplares'];
$idgenero = $_POST['idgenero'];
$estado = $_POST['estado'];

$update = CRUD("UPDATE libros SET isbn='$isbn', titulo='$titulo', autor='$autor', ejemplares='$ejemplares', idgenero='$idgenero', estado='$estado' WHERE idlibro='$idlibro'", "u");
?>
<?php if ($update): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Libro Actualizado");
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Libro No Actalizado");
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php endif ?>
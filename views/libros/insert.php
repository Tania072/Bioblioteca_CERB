<?php 
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';

$isbn = $_POST['isbn'];
$titulo = $_POST['titulo'];
$autor = $_POST['autor'];
$ejemplares = $_POST['ejemplares'];
$idgenero = $_POST['idgenero'];

$insert = CRUD("INSERT INTO libros(isbn, titulo, autor, ejemplares, idgenero) VALUES ('$isbn', '$titulo', '$autor', '$ejemplares', '$idgenero')", "i");
?>
<?php if ($insert): ?>
    <script>
        $(document).ready(function() {
            alertify.success("Libro Registrado");
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php else: ?>
    <script>
        $(document).ready(function() {
            alertify.error("Error Libro No Registrado");
            $("#contenido-principal").load("./views/libros/principal.php");
        });
    </script>
<?php endif ?>
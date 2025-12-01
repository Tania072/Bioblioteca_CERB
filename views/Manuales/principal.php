<?php 
@session_start();
$rol= $_SESSION['idrol'];
$ruta=($rol == 1)? 'Admin/Administrador.pdf':'docente/Docente.pdf';
?>
<div class="container-fluid">
    <embed src="./views/Manuales/<?= $ruta; ?> ?>" type="application/pdf" width="100%" height="500px">
</div>
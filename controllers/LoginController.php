<?php 
@session_start();
include_once '../models/conexion.php';
include_once '../models/funciones.php';
include_once '../controllers/funciones.php';

if(isset($_REQUEST['usuario']) && isset($_REQUEST['password'])){
    $user = $_REQUEST['usuario'];
    $passw = $_REQUEST['password'];
    $tabla = 'usuarios';
    $campo = 'usuario';
    AccesoLogin($user,$passw,$tabla,$campo);
}else {
    header("Location: ../index.php");
    exit();
}
?>
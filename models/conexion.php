<?php
// Definir la clase ConexionBD
class ConexionBD {
    // Variable para mantener la conexión activa
    private $conexion;
    // Metodo para establecer la conexión a la base de datos
    public function get_conexion(){
        // Creamos los parametros de conexión
        $servidor = "localhost";
        $usuario = "root";
        $clave = "";
        $base_datos = "scb";
        try{
            // Creamos una nueva instancia de PDO
            $this->conexion = new PDO("mysql:host=$servidor;dbname=$base_datos;charset=utf8", $usuario, $clave);
            // Modo de error a excepciones
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Conexión exitosa";
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
            $this->conexion = null;
        }
        return $this->conexion;
    }
    // Metodos para cerrar la conexión
    public function close_conexion(){
        $this->conexion = null;
    }
}

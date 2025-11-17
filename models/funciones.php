<?php
class Procesos
{
    // Obtener datos del usuario
    public function GetDataUser($user, $tabla, $campo)
    {
        $row = [];
        $modelo = new ConexionBD();
        $conexion = $modelo->get_conexion();
        if ($conexion) {
            $arg = ":" . $campo;
            $sql = "SELECT * FROM $tabla WHERE $campo = $arg OR email = $arg";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam($arg, $user);
            $stmt->execute();
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $row[] = $result;
            }
            $modelo->close_conexion();
        } else {
            echo "Error de conexión a la base de datos";
        }
        return $row;
    }

    //funcion Insert Select Update Delete
    public function isdu($query, $accion, $params = [])
    {
        $row = [];
        $modelo = new ConexionBD();
        $conexion = $modelo->get_conexion();
        $stmt = null;

        try {
            // 1) Preparar la consulta
            $stmt = $conexion->prepare($query);
            if (!$stmt) {
                return 0;
            }
            // 2) Verificar si la acción es un SELECT
            if ($accion == "S" || $accion == "s") {
                $stmt->execute($params);
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $row[] = $result;
                }
                return $row;
            } else {
                $stmt->execute($params);
                return 1; // Retorna 1 si la acción es INSERT, UPDATE o DELETE
            }
        } catch (PDOException $e) {
            return 0; // Retorna 0 en caso de error
        } finally {
            $stmt = null; // Libera el recurso de la consulta
            $modelo->close_conexion(); // Cierra la conexión
        }
    }

    //Funcion de contar registros
    public function row_data($query, $params = [])
    {
        $modelo = new ConexionBD();
        $conexion = $modelo->get_conexion();
        $stmt = null;

        try {
            // 1) Preparar la consulta
            $stmt = $conexion->prepare($query);
            if (!$stmt) {
                return 0;
            }
            // 2) Ejecutar la consulta
            $stmt->execute($params);
            // Retornar el conteo de los registros
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return 0; // Retorna 0 en caso de error
        } finally {
            $stmt = null; // Libera el recurso de la consulta
            $modelo->close_conexion(); // Cierra la conexión
        }
    }

    //Obtener el ultimo ID insertado de X tabla
    public function max_id($query, $params = [])
    {
        $maxid = null;
        $modelo = new ConexionBD();
        $conexion = $modelo->get_conexion();
        $stmt = $conexion->prepare($query);
        $stmt->execute($params);
        while ($result = $stmt->fetch()) {
            $maxid = $result[0];
        }
        return $maxid;
    }

    /* Busca valor de X tabla*/
    public function BuscaValor($tabla, $campo, $condicion)
    {
        $sql = "SELECT $campo FROM $tabla WHERE $condicion";
        $result = CRUD($sql, "s")[0][$campo];
        return $result;
    }
}

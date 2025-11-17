<?php
function AccesoLogin($user, $passw, $tabla, $campo)
{
    $consultas = new Procesos();
    $datos = $consultas->GetDataUser($user, $tabla, $campo) ?? [];
    // Verificar si se obtuvo algún dato
    if ($datos && is_array(($datos))) {
        foreach ($datos as $result) {
            $idusuario = $result['idusuario'];
            $usuario = $result['usuario'];
            $hash = $result['passw'];
            $idrol = $result['idrol'];
            $estado = $result['estado'];
        }
        if ($estado == 1) {
            if (password_verify($passw, $hash)) {
                @session_start();
                $_SESSION['idusuario'] = $idusuario;
                $_SESSION['usuario'] = $usuario;
                $_SESSION['idrol'] = $idrol;
                $_SESSION['login_ok'] = true;

                echo '<script>
                    // 1) Injerta un spinner dentro de #principal, usando comillas simples
                    $("#principal").html(\'<div style="margin-top:100px;text-align:center;">\' +
                        \'<img src="./public/img/load.gif" alt="loading" />\' +
                        \'<br/><b>Un momento, por favor...</b>\' +
                    \'</div>\');

                    // 2) Muestra un toaster de éxito con Alertify
                    alertify.success("Bienvenido/a");

                    // 3) Tras 2 segundos, redirige a index.php
                    setTimeout(function(){
                        window.location.href = "index.php";
                    }, 2000);
                </script>';
            } else {
                echo '<script>
                    alertify.error("Contraseña incorrecta...");
                    setTimeout(function(){
                        window.location.href = "index.php";
                    }, 1000);
                </script>';
            }
        } else {
            echo '<script>
            alertify.error("Usuario no tiene permisos de acceso...");
            setTimeout(function(){
                window.location.href = "index.php";
            }, 1000);
        </script>';
        }
    } else {
        echo '<script>
            /*
                alertify.alert("Error","El usuario no existe",function(){
                    window.location.href = "index.php";
                });
            */
                alertify.error("Usuario no existe");
                setTimeout(function() {
                    window.location.href = "index.php";
                }, 1000);
                
            </script>';
    }
}

function CRUD($query, $accion) {
    $consultas = new Procesos();
    $data = $consultas->isdu($query, $accion);
    return $data;
}

function CountReg($query){
    $consultas = new Procesos();
    $data = $consultas->row_data($query);
    return $data;
}

function MaxID($query, $params = []){
    $consultas = new Procesos();
    $data = $consultas->max_id($query, $params);
    return $data;
}

function buscavalor($tabla, $campo, $condicion)
{
    $consultas = new Procesos();
    $data = $consultas->BuscaValor($tabla, $campo, $condicion);
    return $data;
}
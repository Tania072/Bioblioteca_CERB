<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
@session_start();

$cont = 0;

if (isset($_REQUEST['num'])) {
    $pagina = $_REQUEST['num'];
} else {
    $pagina = 0;
}

if (isset($_REQUEST['num_reg'])) {
    $registros = $_REQUEST['num_reg'];
} else {
    $registros = 10;
}

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $registros;
}

if (isset($_REQUEST['login'])) {
    $login = $_REQUEST['login'];
    $query = "SELECT * FROM usuarios WHERE usuario LIKE '%$login%' || email LIKE '%$login%'";

    $dataUsuarios = CRUD("SELECT * FROM usuarios WHERE usuario LIKE '%$login%' || email LIKE '%$login%' LIMIT $inicio,$registros", "s");
} else {
    $query = 'SELECT * FROM usuarios';
    $dataUsuarios = CRUD("SELECT * FROM usuarios LIMIT $inicio,$registros", "s");
}

$num_registros = CountReg($query);
$paginas = ceil($num_registros / $registros);

$data = CRUD("SELECT COUNT(*) AS total, 
SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) AS activos, 
SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) AS inactivos,
SUM(CASE WHEN idrol = 1 THEN 1 ELSE 0 END) AS admins, 
SUM(CASE WHEN idrol = 2 THEN 1 ELSE 0 END) AS docentes
FROM usuarios", "s");

$data2 = CRUD("SELECT COUNT(*) AS total, 
SUM(estado = 1) AS activos, 
SUM(estado = 0) AS inactivos, 
SUM(idrol = 1) AS admins, 
SUM(idrol = 2) AS docentes
FROM usuarios", "s");
//$data = CRUD("SELECT COUNT(*) AS total, FROM usuarios", "s");

foreach ($data as $result) {
    $activos = $result['activos'];
    $inactivos = $result['inactivos'];
    $admins = $result['admins'];
    $docentes = $result['docentes'];
    $total = $result['total'];
}
?>

<style>
    /* Estado deshabilitado visual */
    .BtnDeleteUserOff.is-disabled {
        position: relative;
        /* Necesario para posicionar el overlay */
        opacity: 0.6;
        /* Look de deshabilitado */
    }

    /* El overlay cubre todo el botón: evita el click y muestra cursor "prohibido" */
    .BtnDeleteUserOff.is-disabled::after {
        content: "";
        position: absolute;
        inset: 0;
        /* top/right/bottom/left: 0 */
        pointer-events: auto;
        /* Este pseudo-elemento recibe el puntero */
        cursor: not-allowed;
        /* Icono de prohibido */
    }

    /* Opcional: desaturar el ícono */
    .BtnDeleteUserOff.is-disabled i {
        filter: grayscale(1);
        opacity: 0.8;
    }
</style>
<div class="table-responsive" id="PanelUsuarios">
    <div style="margin: 0 auto; width: 100%;" class="barra">
        <a href="" class="btn btn-sm btn-primary" id="new-user">
            <i class="fa-solid fa-user-plus"></i>
        </a>
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar Usuario | Email" name="login">
                <button class="btn btn-outline-secondary" type="button" id="busca-login">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="" class="btn btn-outline btn-warning" id="reload">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordereless table-bordered table-hover table-sm"
                style="margin: 0 auto;width: 100%;">
                <thead class="vertical-align: middle; " class="cc">
                    <tr>
                        <th>N°</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle;" class="cc">
                    <?php foreach ($dataUsuarios as $result): ?>
                        <?php
                        $query = "SELECT * FROM prestamos WHERE idusuario ='" . $result['idusuario'] . "'";
                        $contPrestamos = CountReg($query);
                        ?>
                        <tr>
                            <td><?php echo $cont += 1; ?></td>
                            <td><?php echo $result['usuario']; ?></td>
                            <td><?php echo $result['email']; ?></td>
                            <td><?php echo ($result['idrol'] == 1) ? 'Administrador' : 'Docente'; ?></td>
                            <td><?php echo ($result['estado'] == 1) ? 'Activo' : 'Desactivo'; ?></td>
                            <td>
                                <a href="" class="btn btn-sm btn-success BtnUpdateUser" idusuario="<?php echo $result['idusuario']; ?>">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                            <td>
                                <?php if ($contPrestamos == 0): ?>
                                    <a href="" class="btn btn-sm btn-danger BtnDeleteUser" idusuario="<?php echo $result['idusuario']; ?>">
                                        <i class="fa-solid fa-user-xmark"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="#"
                                        class="btn btn-sm btn-danger BtnDeleteUserOff is-disabled"
                                        idusuario="<?php echo $result['idusuario']; ?>"
                                        aria-disabled="true"
                                        tabindex="-1"
                                        title="Acción deshabilitada">
                                        <i class="fa-solid fa-user-xmark"></i>
                                    </a>
                                <?php endif ?>
                                <?php if ($result["estado"] == 1): ?>
                                    <a href="" class="btn btn-sm btn-warning BtnBAUser" valor="0" idusuario="<?php echo $result['idusuario']; ?>">
                                        <i class="fa-solid fa-user-slash"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="" class="btn btn-sm btn-info BtnBAUser" valor="1" idusuario="<?php echo $result['idusuario']; ?>">
                                        <i class="fa-solid fa-user-check"></i>
                                    </a>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <b>Activos | Inactivos</b>
                </div>
                <div class="card-body">
                    <p><b>Activos: </b><?= $activos; ?></p>
                    <p><b>Inactivos: </b><?= $inactivos; ?></p>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-header">
                    <b>Admin | Docentes</b>
                </div>
                <div class="card-body">
                    <p><b>Admin's: </b><?= $admins; ?></p>
                    <p><b>Docentes: </b><?= $docentes; ?></p>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-header">
                    <b>Total</b>
                </div>
                <div class="card-body">
                    <p><b>Total: </b><?= $total; ?></p>
                </div>
            </div>
        </div>
    </div>

    <?php if ($num_registros > $registros): ?>
        <?php if ($pagina == 1): ?>
            <div class="vh">
                <a href="" class="pagina btn btn-sm btn-d null-envio" v-num="<?php echo ($pagina - 1); ?>" num-reg="<?php echo $registros; ?>" disabled="disabled">
                    <i class="fa-regular fa-circle-left"></i>
                </a>
                &nbsp;&nbsp;
                <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina + 1); ?>" num-reg="<?php echo $registros; ?>">
                    <i class="fa-regular fa-circle-right"></i>
                </a>
            </div>
        <?php elseif ($pagina == $paginas): ?>
            <div class="vh">
                <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina - 1); ?>" num-reg="<?php echo $registros; ?>">
                    <i class="fa-regular fa-circle-left"></i>
                </a>
                &nbsp;&nbsp;
                <a href="" class="btn btn-sm btn-d null-envio" v-num="<?php echo ($pagina + 1); ?>" num-reg="<?php echo $registros; ?>" disabled="disabled">
                    <i class="fa-regular fa-circle-right"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="vh">
                <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina - 1); ?>" num-reg="<?php echo $registros; ?>">
                    <i class="fa-regular fa-circle-left"></i>
                </a>
                &nbsp;&nbsp;
                <a href="" class="pagina btn btn-sm bg-dark text-white" v-num="<?php echo ($pagina + 1); ?>" num-reg="<?php echo $registros; ?>">
                    <i class="fa-regular fa-circle-right"></i>
                </a>
            </div>
        <?php endif ?>
    <?php endif ?>
</div>
<script>
    $(document).ready(function() {
        //reload
        $("#reload").click(function() {
            $("#contenido-principal").load("views/usuarios/principal.php");
            return false;
        });

        // Evitar redirigir
        $(".null-envio").click(function() {
            return false;
        });
        // Paginado
        $(".pagina").click(function() {
            let num, reg;
            num = $(this).attr("v-num");
            reg = $(this).attr("num-reg");
            $("#contenido-principal").load("views/usuarios/principal.php?num=" + num + "&num_reg=" + reg);
            return false;
        });

        // Busca Usuario por Usuario ó Email
        $("#busca-login").click(function() {
            let login = $('[name="login"]').val();
            $.post("views/usuarios/principal.php", {
                login: login
            }, function(html) {
                $("#contenido-principal").html(html);
            });
            return false;
        });

        //Modal Nuevo Usuario
        $("#new-user").click(function() {
            $("#ModalNU").modal('show');
            $("#ModalDNU").load("./views/usuarios/form_insert.php");
            return false;
        });

        //Modal Editar Usuario
        $(".BtnUpdateUser").click(function() {
            let idusuario = $(this).attr('idusuario');
            $("#ModalEU").modal('show');
            $("#ModalDEU").load("./views/usuarios/form_update.php?idusuario=" + idusuario);
            return false;
        });

        //Boton de actualizar estado de usuario
        $(".BtnBAUser").click(function() {
            let idusuario, valor;
            idusuario = $(this).attr('idusuario');
            valor = $(this).attr('valor');
            $("#PanelUsuarios").load('./views/usuarios/estado.php?idusuario=' + idusuario + '&valor=' + valor);
            return false;
        });

        //Boton para eliminar usuario si no tiee registros de prestamos
        $(".BtnDeleteUser").click(function() {
            let idusuario = $(this).attr('idusuario');
            alertify.confirm('Eliminar Usuario', '¿Seguro de eliminar este usuario?',
                function() {
                    $("#PanelUsuarios").load('./views/usuarios/del.php?idusuario=' + idusuario);
                },
                function() {
                    alertify.error('Cancelado');
                });
            return false;
        });
    });
</script>
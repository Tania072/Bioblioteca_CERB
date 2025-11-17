<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
include_once '../modals/nuevo_docente.php';
include_once '../modals/editar_docente.php';

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

if (isset($_REQUEST['valor'])) {
    $valor = $_REQUEST['valor'];
    $query = "SELECT * FROM docentes WHERE nombres LIKE '%$valor%' || email LIKE '%$valor%'";

    $dataDocentes = CRUD("SELECT * FROM docentes WHERE nombres LIKE '%$valor%' || email LIKE '%$valor%' LIMIT $inicio,$registros", "s");
} else {
    $query = 'SELECT * FROM docentes';
    $dataDocentes = CRUD("SELECT * FROM docentes LIMIT $inicio,$registros", "s");
}

$num_registros = CountReg($query);
$paginas = ceil($num_registros / $registros);
$data = CRUD(
    "SELECT COUNT(*)  AS total, 
SUM(CASE WHEN estado = 1 THEN 1 ELSE 0 END) AS activos, 
SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END) AS inactivos
FROM docentes",
    "s"
);

foreach ($data as $result) {
    $activos = $result['activos'];
    $inactivos = $result['inactivos'];
    $total = $result['total'];
}
?>
<div class="table-responsive" id="PanelDocentes">
    <div style="margin: 0 auto; width: 100%;" class="barra">
        <a href="" class="btn btn-sm btn-primary" id="new-docentes">
            <i class="fa-solid fa-user-plus"></i>
        </a>
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar Docentes | Email" name="valor">
                <button class="btn btn-outline-secondary" type="button" id="busca-valor">
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
                        <th>Nombres</th>
                        <th>Email</th>
                        <th>Dui</th>
                        <th>Estado</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                </thead>
                <tbody style="vertical-align: middle;" class="cc">
                    <?php foreach ($dataDocentes as $result): ?>
                        <?php
                        $query = "SELECT * FROM prestamos WHERE idusuario ='" . $result['idusuario'] . "'";
                        $contPrestamos = CountReg($query);
                        $res = CRUD("SELECT usuario FROM usuarios WHERE idusuario='" . $result['idusuario'] . "'", "s");
                        ?>
                        <tr>
                            <td><?php echo $cont += 1; ?></td>
                            <td><?php echo $result['nombres'] . ' ' . $result['apellidos']; ?></td>
                            <td><?php echo $result['email']; ?></td>
                            <td>
                                <?php
                                $idusuario = $result['idusuario'];
                                $dUser = CRUD("SELECT usuario FROM usuarios WHERE idusuario='$idusuario'", "s");
                                echo $usuario = $dUser[0]['usuario'];
                                ?>
                            </td>
                            <td><?php echo ($result['estado'] == 1) ? 'Activo' : 'Desactivo'; ?></td>
                            <td>
                                <a href="" class="btn btn-sm btn-success BtnUpdateDocente" iddocente="<?php echo $result['iddocente']; ?>">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                            <td>
                                <?php if ($contPrestamos == 0): ?>
                                    <a href="" class="btn btn-sm btn-danger BtnDeleteDocente" iddocente="<?php echo $result['iddocente']; ?>">
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
            $("#contenido-principal").load("views/docentes/principal.php");
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
            $("#contenido-principal").load("views/docentes/principal.php?num=" + num + "&num_reg=" + reg);
            return false;
        });

        // Busca Usuario por Usuario ó Email
        $("#busca-valor").click(function() {
            let valor = $('[name="valor"]').val();
            $.post("views/docentes/principal.php", {
                valor: valor
            }, function(html) {
                $("#contenido-principal").html(html);
            });
            return false;
        });

        //Modal Nuevo Docente
        $("#new-docentes").click(function() {
            $("#ModalND").modal('show');
            $("#ModalDND").load("./views/docentes/form_insert.php");
            return false;
        });

        //Modal Editar Docente
        $(".BtnUpdateDocente").click(function() {
            let iddocente = $(this).attr('iddocente');
            $("#ModalED").modal('show');
            $("#ModalDED").load("./views/docentes/form_update.php?iddocente=" + iddocente);
            return false;
        });

        //Boton de actualizar estado de usuario
        $(".BtnBADocente").click(function() {
            let iddocente, valor;
            iddocente = $(this).attr('iddocente');
            valor = $(this).attr('valor');
            $("#PanelUsuarios").load('./views/docentes/estado.php?iddocente=' + iddocente + '&valor=' + valor);
            return false;
        });

        //Boton para eliminar usuario si no tiee registros de prestamos
        $(".BtnDeleteDocente").click(function() {
            let iddocente = $(this).attr('iddocente');
            alertify.confirm('Eliminar Usuario', '¿Seguro de eliminar este usuario?',
                function() {
                    $("#PanelUsuarios").load('./views/docentes/del.php?iddocente=' + iddocente);
                },
                function() {
                    alertify.error('Cancelado');
                });
            return false;
        });
    });
</script>
<?php
include_once '../../models/conexion.php';
include_once '../../models/funciones.php';
include_once '../../controllers/funciones.php';
@session_start();

$cont = 0;

// Obtener el iddocente basado en el usuario de sesión
$idusuario_sesion = $_SESSION['idusuario'] ?? 0;
$iddocente = 0;

if ($idusuario_sesion) {
    $docenteData = CRUD("SELECT iddocente FROM docentes WHERE idusuario='$idusuario_sesion'", "s");
    if (is_array($docenteData) && count($docenteData) > 0) {
        $iddocente = $docenteData[0]['iddocente'];
    }
}

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

if (isset($_REQUEST['libro'])) {
    $libro = $_REQUEST['libro'];
    $query = "SELECT * FROM libros WHERE titulo LIKE '%$libro%' || autor LIKE '%$libro%'";

    $dataLibros = CRUD("SELECT * FROM libros WHERE titulo LIKE '%$libro%' || autor LIKE '%$libro%' LIMIT $inicio,$registros", "s");
} else {
    $query = 'SELECT * FROM libros';
    $dataLibros = CRUD("SELECT * FROM libros LIMIT $inicio,$registros", "s");
}

$num_registros = CountReg($query);
$paginas = ceil($num_registros / $registros);
?>
<style>
    /* Mejoras para la tabla */
    .table-custom {
        width: 95% !important;
        margin: 0 auto;
    }

    .table-custom th {
        white-space: nowrap;
        padding: 12px 8px;
    }

    .table-custom td {
        padding: 10px 6px;
        vertical-align: middle;
    }

    .acciones-column {
        min-width: 100px;
        white-space: nowrap;
    }

    .titulo-column {
        min-width: 180px;
        max-width: 200px;
    }

    .autor-column {
        min-width: 150px;
        max-width: 180px;
    }

    .ubicacion-column {
        min-width: 120px;
    }
</style>
<div class="table-responsive" id="PanelLibrosDocentes">
    <div style="margin: 0 auto; width: 100%;" class="barra">
        <div class="acciones">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar Libro | autor" name="libro">
                <button class="btn btn-outline-secondary" type="button" id="busca-libro">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="" class="btn btn-outline btn-warning" id="reload">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </a>
            </div>
        </div>
    </div>
    <table class="table table-borderless table-bordered table-hover table-sm table-custom">
        <thead style="vertical-align: middle;" class="cc">
            <tr>
                <th width="4%">N°</th>
                <th width="10%">ISBN</th>
                <th class="titulo-column">Nombre</th>
                <th class="autor-column">Autor</th>
                <th width="12%">Género</th>
                <th width="8%">Ejemplares</th>
                <th class="ubicacion-column">Ubicación</th>
                <th width="10%">Estado</th>
                <!--th class="acciones-column">Acción</~th -->
            </tr>
        </thead>
        <tbody style="vertical-align: middle;" class="cc">
            <?php foreach ($dataLibros as $result): ?>
                <?php
                $genero = CRUD("SELECT * FROM generos WHERE idgenero='" . $result['idgenero'] . "'", "s");
                $nombreGenero = (count($genero) > 0) ? $genero[0]['genero'] : "Sin género";
                $ubicacion = CRUD("SELECT * FROM ubicaciones WHERE idubicacion='" . $result['idubicacion'] . "'", "s");
                $nombreUbicacion = (count($ubicacion) > 0) ? $ubicacion[0]['nombre'] : "Sin ubicación";
                ?>
                <tr>
                    <td><?php echo $cont += 1; ?></td>
                    <td><small><?php echo $result['isbn']; ?></small></td>
                    <td class="titulo-column" title="<?php echo $result['titulo']; ?>">
                        <div style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php echo $result['titulo']; ?>
                        </div>
                    </td>
                    <td class="autor-column" title="<?php echo $result['autor']; ?>">
                        <div style="max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?php echo $result['autor']; ?>
                        </div>
                    </td>
                    <td><small><?php echo $nombreGenero; ?></small></td>
                    <td class="text-center">
                        <?php
                        $ejemplares_disponibles = isset($result['ejemplares_disponibles']) ? $result['ejemplares_disponibles'] : $result['ejemplares'];
                        echo $ejemplares_disponibles;
                        ?>
                    </td>
                    <td class="ubicacion-column"><small><?php echo $nombreUbicacion; ?></small></td>
                    <td>
                        <span class="badge <?php echo ($result['estado'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                            <?php echo ($result['estado'] == 1) ? 'Disponible' : 'No disponible'; ?>
                        </span>
                    </td>
                    <!-- td class="acciones-column">
                        <?php if ($result['estado'] == 1): ?>
                            <a href="" class="btn btn-sm btn-primary BtnPrestarLibro" idlibro="<?php echo $result['idlibro']; ?>" title="Solicitar Préstamo">
                                <i class="fa-solid fa-hand-holding"></i> Prestar
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-secondary" disabled title="Libro no disponible">
                                <i class="fa-solid fa-ban"></i> No disponible
                            </button>
                        <?php endif ?>
                    </!-- -->
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <div class="align-items-center text-center mt-3">
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
    <!-- Modal Solicitar Prestamo -->
    <form id="FormSolicitarPrestamo">
        <div class="modal fade" id="ModalSP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                            Solicitar Préstamo
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="ModalDSP">
                        <!-- Aquí se cargará el formulario -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Solicitar Préstamo</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        //reload
        $("#reload").click(function() {
            $("#contenido-principal").load("views/libros/principal_docentes.php");
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
            $("#contenido-principal").load("views/libros/principal_docentes.php?num=" + num + "&num_reg=" + reg);
            return false;
        });

        // Busca Usuario por Libro ó autor
        $("#busca-libro").click(function() {
            let libro = $('[name="libro"]').val();
            $.post("views/libros/principal_docentes.php", {
                libro: libro,
            }, function(html) {
                $("#contenido-principal").html(html);
            });
            return false;
        });
        // Modal Solicitar Préstamo - Reemplazar el confirm actual
        $(".BtnPrestarLibro").click(function() {
            let idlibro = $(this).attr('idlibro');
            let iddocente = "<?php echo $iddocente; ?>";

            if (iddocente === "0") {
                alertify.alert("Solicitar Préstamo", "No se encontró su registro como docente. Contacte al administrador.");
                return false;
            }

            $("#ModalSP").modal('show');
            $("#ModalDSP").load("./views/prestamos/form_solicitar.php?idlibro=" + idlibro + "&iddocente=" + iddocente);
            return false;
        });

        // Enviar formulario de solicitud
        $("#FormSolicitarPrestamo").submit(function(e) {
            e.preventDefault();

            let cantidad = $("input[name='cantidad']").val();
            let fecharetorno = $("input[name='fecharetorno']").val();

            if (cantidad === "" || cantidad === null || cantidad <= 0) {
                alertify.alert("Solicitar Préstamo", "Favor de ingresar una cantidad válida.");
                $("[name='cantidad']").focus();
                return false;
            }

            if (fecharetorno === "" || fecharetorno === null) {
                alertify.alert("Solicitar Préstamo", "Favor de seleccionar la fecha de devolución.");
                $("[name='fecharetorno']").focus();
                return false;
            }

            let formData = new FormData(this);

            $.ajax({
                url: "./views/prestamos/solicitar_insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalSP").modal('hide');
                    $("#contenido-principal").html(result);
                },
                error: function() {
                    alertify.error("Error en el servidor intente nuevamente...");
                }
            });
        });
    });
</script>
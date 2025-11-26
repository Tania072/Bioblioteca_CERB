<!-- Modal Editar Prestamo-->
<form id="FormEditarPrestamo">
    <div class="modal fade" id="ModalEP" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Editar Préstamo
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDEP">
                    <!-- Aquí se cargará el formulario de edición -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#FormEditarPrestamo").submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let idrol_sesion = "<?php echo isset($_SESSION['idrol']) ? $_SESSION['idrol'] : 1; ?>";

            // Determinar la URL según el rol
            let url = "./views/prestamos/update.php";
            /*
                if (idrol_sesion == 2) {
                    url = "./views/prestamos/update_docente.php";
                }
            */
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalEP").modal('hide');
                    $("#contenido-principal").html(result);
                },
                error: function() {
                    alertify.error("Error en el servidor intente nuevamente...");
                }
            });
        });
    });
</script>
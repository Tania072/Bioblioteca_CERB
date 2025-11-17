<!-- Modal Editar Ubicación-->
<form id="Upd-Ubicacion">
    <div class="modal fade" id="ModalEUB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Editar Ubicación
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDEUB"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        //Actualizar Ubicación
        $("#Upd-Ubicacion").submit(function(e) {
            e.preventDefault();
            let nombre, descripcion;
            nombre = $("input[name='nombre']").val();
            descripcion = $("textarea[name='descripcion']").val();

            if (nombre === "") {
                alertify.alert("Actualizar Ubicación", "Favor de ingresar el nombre de la ubicación.");
                $("[name='nombre']").focus();
                return false;
            }

            if (descripcion === "") {
                alertify.alert("Actualizar Ubicación", "Favor de ingresar la descripción de la ubicación.");
                $("[name='descripcion']").focus();
                return false;
            }

            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/ubicaciones/update.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalEUB").modal('hide');
                    $("#PanelUbicaciones").html(result);
                },
                error: function() {
                    $("#PanelUbicaciones").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });

        });
    });
</script>
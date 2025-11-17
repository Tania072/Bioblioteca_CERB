<!-- Modal nueva Ubicación -->
<form id="New-Ubicacion">
    <div class="modal fade" id="ModalNUB" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Nueva Ubicación
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDNUB"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        $("#New-Ubicacion").submit(function(e) {
            e.preventDefault();
            let nombre, descripcion;

            nombre = $("[name='nombre']").val();
            descripcion = $("[name='descripcion']").val();

            if (nombre === "" || nombre === null) {
                alertify.alert("Registro de Nueva Ubicación", "Favor de ingresar el nombre de la ubicación.", function() {
                    $("[name='nombre']").focus();
                });
                return false;
            }

            if (descripcion === "" || descripcion === null) {
                alertify.alert("Registro de Nueva Ubicación", "Favor de ingresar la descripción de la ubicación.", function() {
                    $("[name='descripcion']").focus();
                });
                return false;
            }

            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/ubicaciones/insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalNUB").modal('hide');
                    $("#PanelUbicaciones").html(result);
                },
                error: function() {
                    $("#PanelUbicaciones").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });
        });
    });

</script>

        
<!-- Modal Nuevo Usuario-->
<form id="Upd-Perfil">
    <div class="modal fade" id="ModalEDU" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Editar Perfil
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDDU"></div>
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
        // Guardar Nuevo Usuario
        $("#Upd-Perfil").submit(function(e) {
            e.preventDefault();
            let  email;
            email = $("textarea[name='email']").val();

            if (email === "") {
                alertify.alert("Actualizar Docente", "Favor de ingresar el correo..", function() {
                    $("[name='email']").focus();
                });
                return false;
            }

            if (!email.includes("@")) {
                alertify.alert("Actualizar Docente", "El correo debe incluir '@'.", function() {
                    $("[name='email']").focus();
                });
                return false;
            }

            const allowed = /^[^\s@]+@(gmail\.com|hotmail\.com|yahoo\.com|outlook\.com|live\.com|uls\.edu\.sv)$/i;

            if (!allowed.test(email.trim())) {
                alertify.alert(
                    "Actualizar Docente",
                    "Formato de correo no permitido. Usa: @gmail.com, @hotmail.com, @yahoo.com, @outlook.com o @live.com o @uls.edu.sv",
                    function() {
                        $("[name='email']").focus();
                    }
                );
                return false;
            }


            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/perfil/update.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalEDU").modal('hide');
                    $("#contenido-principal").html(result);
                },
                error: function() {
                    $("#contenido-principal").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });
        });
    });
</script>
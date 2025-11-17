<!-- Modal Nuevo Usuario-->
<form id="New-User">
    <div class="modal fade" id="ModalNU" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Nuevo Usuario
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ModalDNU"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        // Guardar Nuevo Usuario
        $("#New-User").submit(function(e) {
            e.preventDefault();
            let usuario, passw, email, idrol;
            usuario = $("input[name='usuario']").val();
            passw = $("input[name='passw']").val();
            email = $("textarea[name='email']").val();
            idrol = $("select[name='idrol']").val();

            if (usuario === "") {
                alertify.alert("Registro de Nuevo Usuario", "Favor de  ingresar el nombre de usuario..");
                $("[name='usuario']").focus();
                return false;
            }

            if (passw === "") {
                alertify.alert("Registro de Nuevo Usuario", "Favor de  ingresar el la contraseña..");
                $("[name='passw']").focus();
                return false;
            }

            if (email === "") {
                alertify.alert("Registro de Nuevo Usuario", "Favor de ingresar el correo..", function() {
                    $("[name='email']").focus();
                });
                return false;
            }

            if (!email.includes("@")) {
                alertify.alert("Registro de Nuevo Usuario", "El correo debe incluir '@'.", function() {
                    $("[name='email']").focus();
                });
                return false;
            }

            const allowed = /^[^\s@]+@(gmail\.com|hotmail\.com|yahoo\.com|outlook\.com|live\.com|uls\.edu\.sv)$/i;

            if (!allowed.test(email.trim())) {
                alertify.alert(
                    "Registro de Nuevo Usuario",
                    "Formato de correo no permitido. Usa: @gmail.com, @hotmail.com, @yahoo.com, @outlook.com o @live.com o @uls.edu.sv",
                    function() {
                        $("[name='email']").focus();
                    }
                );
                return false;
            }

            if (idrol === "" || idrol === "0" || idrol === null) {
                alertify.alert("Registro de Nuevo Usuario", "Seleccione un rol.", function() {
                    $("[name='idrol']").focus();
                });
                return false;
            }
            let formData = new FormData(this);
            formData.append("dato", "valor");

            $.ajax({
                url: "./views/usuarios/insert.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#ModalNU").modal('hide');
                    $("#PanelUsuarios").html(result);
                },
                error: function() {
                    $("#PanelUsuarios").html('<p><b style="text-align:center;color:red;">Error en el servidor intente nuevamente...</b></p>');
                }
            });
        });
    });
</script>
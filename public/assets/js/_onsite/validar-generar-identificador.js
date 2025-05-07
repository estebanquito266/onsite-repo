function validarGenerarNumeroTerminal() {

    var idEmpresaOnsite = $("#terminal_empresa_onsite_id").val();

    if(!idEmpresaOnsite) {
        idEmpresaOnsite = $("#id_empresa_onsite").val();
    }

    var route = "/getEmpresaOnsite/" + idEmpresaOnsite;

    $.get(route, function (response, state) {
        if(response.generar_clave_reparacion == 1) {
            $('#nro').val('');
            if(!$("#id_empresa_onsite").val()) {
                $( "#nro" ).rules("remove");
            }
        } else {
            //$( "#nro" ).rules("add", "required");
        }
        $('#nro').attr('readonly', (response.generar_clave_reparacion == 1));
    });
}
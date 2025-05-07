$(document).ready(function () {

    //-----------------------------------------------------------------------//		

    $("#id_empresa_onsite").change(function () { // p/buscar
        limpiar();
        //getSucursales();
        validarGenerarClaveReparacion();

    });

    $("#refreshSucursal").click(function () { // p/buscar
        limpiar();
        // getSucursales();
    });

    //-----------------------------------------------------------------------//		

    $("#sucursal_onsite_id").change(function () { // p/buscar
        limpiarTerminal();
        getTerminales();
    });

    $("#refreshTerminal").click(function () { // p/buscar
        limpiarTerminal();
        getTerminales();
    });

    //-----------------------------------------------------------------------//		

    //REPARACIONES ONSITE
    $("#reparacionesOnsiteForm").validate({
        rules: {
            //clave: "required",
            id_empresa_onsite: "required",
            sucursal_onsite_id: "required",
            id_terminal: "required",

            id_tipo_servicio: "required",
            id_estado: "required"
        },
        messages: {
            //clave: "Por favor, ingrese una clave",
            id_empresa_onsite: "Por favor, seleccione una empresa",
            sucursal_onsite_id: "Por favor, seleccione una sucursal",
            id_terminal: "Por favor, seleccione una terminal",

            id_tipo_servicio: "Por favor, seleccione un tipo de servicio",
            id_estado: "Por favor, seleccione un estado",
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });

    //-----------------------------------------------------------------------//		


    /*
    $("#buscarSucursalReparacion").click(function () { // p/buscar
        console.log('#buscarSucursalReparacion--');
        var idEmpresaOnsite = $("#id_empresa_onsite").val();
        var textoBuscar = $("#textoBuscarSucursal").val();
        var i = 0;
        var route = "/searchSucursalesOnsite/" + idEmpresaOnsite + "/" + textoBuscar;

        $.get(route, function (response, state) {

            deshabilitar(); // al buscar, antes, limpio selects y deshabilito botones

            if (response.length <= 0)
                $("#sucursal_onsite_id").append("<option selected='selected' value=''>Sucursal no encontrada</option>");

            if (response.length > 1)
                $("#sucursal_onsite_id").append("<option selected='selected' value=''>Seleccione la sucursal - </option>");

            for (i = 0; i < response.length; i++) {
                $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] - " + response[i].localidad + "</option>");
            }

            if (i == 1) //si es una sola terminal
                $('#editarSucursal').prop('disabled', false);
        });

    });
    */

    //---------------------------------------------------------------------------//

    $("#sucursal_onsite_id").change(function () { // si cambia 

        var idSucursal = $('#sucursal_onsite_id').val();

        if (idSucursal) {
            $('#editSucursal').prop('disabled', false);
        }
        else {
            $('#editSucursal').prop('disabled', true);
            //$("#id_terminal_reparacion").empty();
        }

    });

    //---------------------------------------------------------------------------//	

    $("#createSucursal").click(function () {
        limpiarModalFormSucursal();

        $('#storeModalSucursal').removeClass('d-none');

        $('#updateModalSucursal').addClass('d-none');

        var idEmpresaOnsite = $('#id_empresa_onsite').val();
        //$('#datosSucursales input[name=nro]').attr('readonly', false);
        $('#datosSucursales select[name=empresa_onsite_id]').val(idEmpresaOnsite);
        $('#datosSucursales select[name=empresa_onsite_id]').attr('readonly', true);
    });


    //---------------------------------------------------------------------------//	
    $("#storeModalSucursal").click(function () { // si dentro del modal hace click en guardar, guarda datos		
        var datosSucursales = $("#datosSucursales").serialize();
        var route = "/sucursalesOnsite";
        //var token = $("#token").val();

        $.ajax({
            url: route,
            //headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: datosSucursales,
            success: function (data) {
                $("#modalSucursales").modal('toggle');
                $("#sucursal_onsite_id").empty();
                $("#sucursal_onsite_id").append("<option value='" + data.id + "'> " + data.razon_social + " [" + data.codigo_sucursal + "] </option>");
                $('#editarSucursal').prop('disabled', false); //habilito para editar								
            },
            error: function (data) {
                var mensajeErrorSucursal = 'Ha ocurrido un error en los siguientes campos: <br>';

                $.each(JSON.parse(data.responseText).errors, function (key, value) {
                    mensajeErrorSucursal = mensajeErrorSucursal + key + ': ' + value + ' <br>';
                });
                $('#mensaje-error-sucursal').css('display', 'block');
                $('#mensaje-error-sucursal').html(mensajeErrorSucursal);
            }
        });

    });

    //---------------------------------------------------------------------------//

    $("#editSucursal").click(function () { // si hace click en editar obtengo los datos
        $('#storeModalSucursal').addClass('d-none');
        $('#updateModalSucursal').removeClass('d-none');

        var idSucursal = $('#sucursal_onsite_id').val();
        //var route = "/sucursalesOnsite/"+idSucursal+"/edit";
        var route = "/sucursalesOnsite/" + idSucursal;

        limpiarModalFormSucursal();

        $.get(route, function (res) {

            $.each(res, function (key, value) {

                if (key == 'empresa_onsite_id') {
                    $('#datosSucursales select[name=empresa_onsite_id]').val(value);
                }
                else if (key == 'localidad_onsite_id') {
                    $('#datosSucursales select[name=localidad_onsite_id]').val(value);
                }
                else {
                    $('#datosSucursales input[name=' + key + ']').val(value);
                }

            });
        });

        //$('#datosSucursales input[name=id]').attr('readonly', true);

    });


    //---------------------------------------------------------------------------//

    $("#updateModalSucursal").click(function () { // si dentro del modal hace click en guardar, guarda los datos		
        var datosSucursales = $("#datosSucursales").serialize();
        var idSucursal = $('#sucursal_onsite_id').val();

        var route = "/sucursalesOnsite/" + idSucursal;
        //var token = $("#token").val();

        $.ajax({
            url: route,
            //headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: datosSucursales,
            success: function (data) {
                $("#modalSucursales").modal('toggle');
                $("#sucursal_onsite_id").empty();
                $("#sucursal_onsite_id").append("<option value='" + data.id + "'> " + data.razon_social + " [" + data.codigo_sucursal + "]</option>");

            },
            error: function (data) {
                var mensajeErrorSucursal = 'Ha ocurrido un error en los siguientes campos: <br>';
                $.each(JSON.parse(data.responseText), function (key, value) {
                    mensajeErrorSucursal = mensajeErrorSucursal + key + ': ' + value + ' <br>';
                });

                $('#mensaje-error-sucursal').css('display', 'block');
                $('#mensaje-error-sucursal').html(mensajeErrorSucursal);
            }
        });

    });


    //---------------------------------------------------------------------------//

    $("#id_terminal").change(function () { // si cambia 

        var idTerminal = $('#id_terminal').val();

        if (idTerminal) {
            $('#editTerminal').prop('disabled', false);
        }
        else {
            $('#editTerminal').prop('disabled', true);
            //$("#id_terminal_reparacion").empty();
        }

    });


    //---------------------------------------------------------------------------//	

    $("#createTerminal").click(function () {
        limpiarModalFormTerminal();

        validarGenerarNumeroTerminal();

        $("#terminal_empresa_onsite_id").val($("#id_empresa_onsite").val());
        getSucursalesTerminales();

        $('#storeModalTerminal').removeClass('d-none');

        $('#updateModalTerminal').addClass('d-none');

        var idEmpresaOnsite = $('#id_empresa_onsite').val();
        var idSucursalOnsite = $('#sucursal_onsite_id').val();

        //$('#datosSucursales input[name=nro]').attr('readonly', false);
        $('#datosTerminales select[name=empresa_onsite_id]').val(idEmpresaOnsite);
        $('#datosTerminales select[name=empresa_onsite_id]').attr('readonly', true);

        $('#datosTerminales select[name=sucursal_onsite_id]').val(idSucursalOnsite);
        //$('#datosTerminales select[name=sucursal_onsite_id]').attr('readonly', true);

        //$('#datosTerminales input[name=id_localidad]').val(1);
        //$('#datosTerminales input[name=id_localidad]').attr('readonly', true);
    });


    //---------------------------------------------------------------------------//	
    $("#storeModalTerminal").click(function () { // si dentro del modal hace click en guardar, guarda datos		
        var datosTerminales = $("#datosTerminales").serialize();
        var route = "/terminalOnsite";
        //var token = $("#token").val();

        $.ajax({
            url: route,
            //headers: {'X-CSRF-TOKEN': token},
            type: 'POST',
            dataType: 'json',
            data: datosTerminales,
            success: function (data) {
                $("#modalTerminales").modal('toggle');
                $("#id_terminal").empty();
                $("#id_terminal").append("<option value='" + data.nro + "'> " + data.nro + " - " + data.marca + " - " + data.modelo + " - " + data.serie + "</option>");
                $('#editTerminal').prop('disabled', false); //habilito para editar								
            },
            error: function (data) {
                var mensajeErrorTerminal = 'Ha ocurrido un error en los siguientes campos: <br>';
                $.each(JSON.parse(data.responseText), function (key, value) {
                    mensajeErrorTerminal = mensajeErrorTerminal + key + ': ' + value + ' <br>';
                });
                $('#mensaje-error-terminal').css('display', 'block');
                $('#mensaje-error-terminal').html(mensajeErrorTerminal);
            }
        });

    });

    //---------------------------------------------------------------------------//

    $("#editTerminal").click(function () { // si hace click en editar obtengo los datos
        $('#storeModalTerminal').addClass('d-none');
        $('#updateModalTerminal').removeClass('d-none');

        var idTerminal = $('#id_terminal').val();
        var route = "/terminalOnsite/" + idTerminal + "/edit";

        limpiarModalFormTerminal();

        $.get(route, function (res) {

            $.each(res, function (key, value) {

                if (key == 'empresa_onsite_id') {
                    $('#datosTerminales select[name=empresa_onsite_id]').val(value);
                }
                else if (key == 'sucursal_onsite_id') {
                    $('#datosTerminales select[name=sucursal_onsite_id]').val(value);
                }
                else {
                    $('#datosTerminales input[name=' + key + ']').val(value);
                }

            });
        });

        $('#datosTerminales input[name=nro]').attr('readonly', true);

    });

    //---------------------------------------------------------------------------//

    $("#updateModalTerminal").click(function () { // si dentro del modal hace click en guardar, guarda los datos		
        var datosTerminales = $("#datosTerminales").serialize();
        var idTerminal = $('#id_terminal').val();

        var route = "/terminalOnsite/" + idTerminal;
        //var token = $("#token").val();

        $.ajax({
            url: route,
            //headers: {'X-CSRF-TOKEN': token},
            type: 'PUT',
            dataType: 'json',
            data: datosTerminales,
            success: function (data) {
                $("#modalTerminales").modal('toggle');
                $("#id_terminal").empty();
                $("#id_terminal").append("<option value='" + data.nro + "'> " + data.nro + " - " + data.marca + " - " + data.modelo + " - " + data.serie + "</option>");

            },
            error: function (data) {
                var mensajeErrorTerminal = 'Ha ocurrido un error en los siguientes campos: <br>';
                $.each(JSON.parse(data.responseText), function (key, value) {
                    mensajeErrorTerminal = mensajeErrorTerminal + key + ': ' + value + ' <br>';
                });

                $('#mensaje-error-terminal').css('display', 'block');
                $('#mensaje-error-terminal').html(mensajeErrorTerminal);
            }
        });

    });

    //---------------------------------------------------------------------------//
    $('#btn_nuevo_activo').on('click', function () {
        var activoNro = $('#btn_nuevo_activo').val();
        console.log("#activo_"+activoNro);

        $("#activo_"+activoNro).removeClass('d-none');
        $('#btn_nuevo_activo').val(parseInt(activoNro)+1);
    });

});

//---------------------------------------------------------------------------//
//---------------------------------------------------------------------------//
function limpiar() {
    limpiarSucursal();
    limpiarTerminal();
}

function limpiarSucursal() {
    $("#sucursal_onsite_id").empty();
}

function limpiarTerminal() {
    $("#id_terminal").empty();
}

function getSucursales() {
    var idEmpresaOnsite = $("#id_empresa_onsite").val();

    var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite;

    $.get(route, function (response, state) {

        limpiar(); // al buscar, antes, limpio selects y deshabilito botones

        if (response.length <= 0)
            $("#sucursal_onsite_id").append("<option selected='selected' value=''>Sucursal Onsite no encontrada</option>");

        if (response.length > 1)
            $("#sucursal_onsite_id").append("<option selected='selected' value=''>Seleccione la sucursal onsite - </option>");

        for (i = 0; i < response.length; i++) {
            $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] " + response[i].localidad + "</option>");
        }


    });
}

function getTerminales() {
    var idSucursalOnsite = $('#sucursal_onsite_id').val();
    var route = "/buscarTerminalesOnsite/" + idSucursalOnsite;

    $.get(route, function (response, state) {

        limpiarTerminal(); // al buscar, antes, limpio selects y deshabilito botones

        if (response.length <= 0)
            $("#id_terminal").append("<option selected='selected' value=''>Terminal Onsite no encontrada</option>");

        if (response.length > 1)
            $("#id_terminal").append("<option selected='selected' value=''>Seleccione la terminal onsite - </option>");

        for (i = 0; i < response.length; i++) {
            $("#id_terminal").append("<option value='" + response[i].nro + "'> " + response[i].nro + " - " + response[i].marca + " - " + response[i].modelo + " - " + response[i].serie + "</option>");
        }


    });



}

//---------------------------------------------------------------------------//
function deshabilitar() {
    $("#sucursal_onsite_id").empty();
    $('#editarSucursal').prop('disabled', true);
}

//---------------------------------------------------------------------------//

function limpiarModalFormSucursal() {
    $('#datosSucursales')[0].reset();
    $('#mensaje-error-sucursal').html('');
    $('#mensaje-error-sucursal').css('display', 'none');
}

//---------------------------------------------------------------------------//

function limpiarModalFormTerminal() {
    $('#datosTerminales')[0].reset();
    $('#mensaje-error-terminal').html('');
    $('#mensaje-error-terminal').css('display', 'none');
}

//---------------------------------------------------------------------------//

function validarGenerarClaveReparacion() {

    var idEmpresaOnsite = $("#id_empresa_onsite").val();

    var route = "/getEmpresaOnsite/" + idEmpresaOnsite;

    $.get(route, function (response, state) {
        if (response.generar_clave_reparacion == 1) {
            $('#clave').val('');
        }
        $('#clave').attr('readonly', (response.generar_clave_reparacion == 1));
    });
}
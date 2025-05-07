$(function () {

    var crearnuevo = 0;

    cargandoBgh = makeLoader('Cargando datos de empresa y obras');
    $('#cargando_bgh').html(cargandoBgh);
    setTimeout(function () {
        $('#cargando_bgh').html('');
    }, 2500
    );

    setTimeout(function () {
        resetSmartWizard();
        $('.formulario_obra').removeAttr('hidden');
    }, 3000
    );

    /* muestro provincia y localidad segun el pais */
    pais = $('#select_pais').val();
    showLocalidades(pais);

    /* actualiza datos obra */

    $('#refreshsistemasbutton').on('click', function (e) {
        e.preventDefault();
        sistemaid = $('#sistema_onsite_id').find(':selected').val();
        idObra = $('#sistema_onsite_id').find(':selected').data('idobra');      


        nombreSistema = $('sistema_onsite_id').find(':selected').data('nombre_sistema');

        $('#obra_onsite_id').val(idObra);
        $('#obra_nombre').val(nombreSistema);
        var idEmpresaOnsite = $("#empresa_onsite_id").val();
        getObraOnsite(idObra);
        limpiarSucursal();
		getSucursalesOnsite(idEmpresaOnsite);
    });

    $('#addobrabutton').on('click', function (e) {
        e.preventDefault();
        $('#datos_obra_div').attr('hidden', false);
        crearnuevo = 1;


    });

    $('#obraOnsite').on('change', function () {
        idObra = $(this).val();
        getObraOnsite(idObra);

    });

    $('#select_pais').on('change', function () {

        pais = $(this).val();
        showLocalidades(pais);
    })


    $('#provincia').on('change', function () {
        idProvincia = $(this).val();
        getLocalidades(idProvincia);
    });

    $('#botonGuardar').on('click', function (e) {

        e.preventDefault();
        showToast('Registro Creado correctamente', 'confirmacion', 'success');
        $('#comentarios').val('');

        storeSistema();

    });

    $('#next-btn').on('click', function () {

        /* chequeo si es el último step del wizard form */
        segment = $(location).attr('href');
        largo = parseInt($(location).attr('href').length) - 1;

        if (segment[largo] == 1) {
            $(this).hide();
            validateForm();
        };

        if (crearnuevo == 1) {
            
            createObraForm();
        }


    });

    $('#prev-btn').on('click', function () {

        $('#next-btn').show();

    });

    $('.nav-item').on('click', function () {

        $('#next-btn').show();

    });

    $('#sistema_onsite_id').on('change', function () {

        sistemaid = $(this).find(':selected').val();
        idObra = $(this).find(':selected').data('idobra');

        nombreSistema = $(this).find(':selected').data('nombre_sistema');
      
        $('#obra_onsite_id').val(idObra);
        $('#obra_nombre').val(nombreSistema);
        var idEmpresaOnsite = $("#empresa_onsite_id").val();

        getObraOnsite(idObra);
        limpiarSucursal();
		getSucursalesOnsite(idEmpresaOnsite);

    });


    $('#boton_enviar').on('click', function (e) {
        e.preventDefault();
        storeSolicitud();
    });

    

});


function resetSmartWizard() {


    $('#smartwizard').smartWizard("reset");
    return true;

}

function getObraOnsite(idObra) {
    var rutaModelos = "/getObraOnsite/" + idObra;


    $.get(rutaModelos, function (response, state) {


        if (response.length <= 0) {
            console.log('sin resultados');
        }
        else {
            
            /* $('#datos_obra_div').attr('hidden', false); */

            $('#nombre').val(response.nombre);
            $('#nro_cliente_bgh_ecosmart').val(response.nro_cliente_bgh_ecosmart);
            $('#domicilio').val(response.domicilio);
            $('#cantidad_unidades_exteriores').val(response.cantidad_unidades_exteriores);
            $('#cantidad_unidades_interiores').val(response.cantidad_unidades_interiores);
            $('#estado').val(response.estado);
            $('#estado_detalle').val(response.estado_detalle);

            $('#select_pais').val(response.pais);
            $('#select_pais').trigger('change');

            $('#provincia').val(response.provincia_onsite_id);
            $('#provincia').trigger('change');

            $('#localidad').val(response.localidad_onsite_id);
            $('#localidad').trigger('change');           
            $('#empresa_onsite_id').val(response.empresa_onsite.id);                     

            //$('#esquema').val(response.esquema);

            if (response.obra_checklist_onsite.requiere_zapatos_seguridad == 1) {
                $('#requiere_zapatos_seguridad').val(response.obra_checklist_onsite.requiere_zapatos_seguridad);
                $('#requiere_zapatos_seguridad').bootstrapToggle('on');
            }

            if (response.obra_checklist_onsite.requiere_casco_seguridad == 1) {
                $('#requiere_casco_seguridad').val(response.obra_checklist_onsite.requiere_casco_seguridad);
                $('#requiere_casco_seguridad').bootstrapToggle('on');
            }

            if (response.obra_checklist_onsite.requiere_proteccion_auditiva == 1) {
                $('#requiere_proteccion_auditiva').val(response.obra_checklist_onsite.requiere_proteccion_auditiva);
                $('#requiere_proteccion_auditiva').bootstrapToggle('on');
            }

            if (response.obra_checklist_onsite.requiere_art == 1) {
                $('#requiere_art').val(response.obra_checklist_onsite.requiere_art);
                $('#requiere_art').bootstrapToggle('on');
            }

            if (response.obra_checklist_onsite.requiere_proteccion_visual == 1) {
                $('#requiere_proteccion_visual').val(response.obra_checklist_onsite.requiere_proteccion_visual);
                $('#requiere_proteccion_visual').bootstrapToggle('on');
            }

            if (response.obra_checklist_onsite.clausula_no_arrepentimiento == 1) {
                $('#clausula_no_arrepentimiento').val(response.obra_checklist_onsite.clausula_no_arrepentimiento);
                $('#clausula_no_arrepentimiento').bootstrapToggle('on');
            }


            $('#cuit').val(response.obra_checklist_onsite.cuit);
            $('#razon_social').val(response.obra_checklist_onsite.razon_social);
            $('#cnr_detalle').val(response.obra_checklist_onsite.cnr_detalle);

            $('#esquema_obra').html(
                '<img src="/imagenes/reparaciones_onsite/'
                + response.esquema
                + '" width=100>'
            );





        }



    });
}

function getLocalidades(idProvincia) {

    $("#localidad").html('');

    var rutaModelos = "/getLocalidades/" + idProvincia;


    $.get(rutaModelos, function (response, state) {

        if (response.length <= 0)
            $("#localidad").append("<option selected='selected' value=''>Localidad</option>");

        if (response.length > 1)
            $("#localidad").append("<option selected='selected' value=''>Seleccione la localidad onsite - </option>");

        for (i = 0; i < response.length; i++) {
            $("#localidad").append("<option value='" + response[i].localidad + "'> " + response[i].localidad + "</option>");
        }



    });
}

function validateForm() {
    obra = $('#nombre').val();

    if (obra.length > 0) {

        $('.swal2-error').attr("hidden", true);
        $('.swal2-success').attr("hidden", false);
        $('#boton_enviar').removeClass('btn-warning');
        $('#boton_enviar').addClass('btn-success');
        $('#boton_enviar').attr("disabled", false);

        showResumen();
    }

    else {

        $('#boton_enviar').removeClass('btn-success');
        $('#boton_enviar').addClass('btn-warning');

        $('#boton_enviar').attr("disabled", true);
        $('.swal2-error').attr("hidden", false);
        $('.swal2-success').attr("hidden", true);

        $('.resumen_form').html(
            '<span>Revise errores de carga</span>'
        );
    }
}

function showResumen() {
    $('.resumen_form').html(
        '<span>'
        + 'Empresa Instaladora: '
        + $("#empresa_instaladora_nombre").val()
        + '<br>'


        + 'Obra: '
        + $("#nombre").val()
        + '<br>'

        + '</span>'
    );
}

function showLocalidades(pais) {
    if (pais == 'Argentina') {
        $('#provincia_div').removeAttr('hidden');
        $('.localidad_div').html(
            '<label>Localidad</label>'
            + '<select name="localidad" id="localidad" class="form-control multiselect-dropdown  mb-3">'
            + '</select> '
        );

    }

    else {
        $('#provincia_div').prop('hidden', 'hidden');
        $('.localidad_div').html(
            '<label>Localidad</label>'
            + '<input type="text" name="localidad" id="localidad" class="form-control  mb-3">'
            + '</input> '
        );

    }
}


function getSucursalesOnsite(idEmpresaOnsite) {
    
     var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite ;

    $.get(route, function (response, state) {
        limpiarSucursal(); // al buscar, antes, limpio selects y deshabilito botones

        if (response.length <= 0)
            $("#sucursal_onsite_id").append("<option selected='selected' value=''>Sucursal Onsite no encontrada</option>");

        if (response.length > 1)
            $("#sucursal_onsite_id").append("<option selected='selected' value=''>Seleccione la sucursal onsite - </option>");

        for (i = 0; i < response.length; i++) {
            $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] " + "</option>");
        }


    }); 
}
function limpiarSucursal() {
	/* $("#sucursal_onsite_id").empty(); */
}

function showToast(mensaje, contexto, tipo) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "onclick": true
    }
    

    //toastr["success"]("Repuesto agregado correctamente. Click para ir al carrito", "REPUESTO ONSITE");
    toastr[tipo](mensaje); //se quita el titulo

}

function storeSistema() {

        
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/storeSistema',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            orden_respuestos_id: idOrden,
           

        },
        dataType: 'JSON',
        success: function (data) {
            console.log(data);          


        }
    }
    );
}

function storeSolicitud() {
    dataForm = setDataStoreSolicitud(); 

    $.ajax({
        url: '/storeSolicitud',
        type: 'POST',
        data: dataForm,        
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Registro creado correctamente: ' + data.id, '', 'success');
           
            setTimeout(function () {                
                window.location.href = "/SolicitudPuestaMarcha";
            }, 2000);


        },
        fail: function (data) {
            showToast(data, '', 'error');
        },
        error: function (data) {
            errores = data.responseJSON.errors;
            for (var key in errores) {
                if (errores.hasOwnProperty(key)) {
                    showToast(errores[key], '', 'error');
                }
            }
        }
    }
    );
}

function setDataStoreSolicitud() {
    idSistema = $('#sistema_onsite_id').val();
    idObra = $('#sistema_onsite_id').find(':selected').data('idobra');
    idTipoSolicitud = $('#solicitud_tipo_id').val();
    observaciones = $('#observaciones_internas').val();
    nota_cliente = $('#nota_cliente').val();   
    
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    /* ************************ */

    var dataForm = new FormData();
    dataForm.append('_token', CSRF_TOKEN);
    dataForm.append('obra_onsite_id', idObra);
    dataForm.append('sistema_onsite_id', idSistema);
    dataForm.append('estado_solicitud_onsite_id', 1);
    dataForm.append('solicitud_tipo_id', idTipoSolicitud);
    dataForm.append('observaciones_internas', observaciones);
    dataForm.append('nota_cliente', nota_cliente);
    
    return dataForm;
}
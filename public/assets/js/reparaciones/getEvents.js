$(function () {

   
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

    $('#empresa_instaladora_admins').on('change', function () {
        idEmpresaInstaladora = $(this).find(':selected').val();
        nombreEmpresaInstaladora = $(this).find(':selected').text();
        email = $(this).find(':selected').data('email');
        nombre = $(this).find(':selected').data('nombre');
        telefono = $(this).find(':selected').data('telefono');

        $('#empresa_instaladora_id').val(idEmpresaInstaladora);
        $('#empresa_instaladora_nombre').val(nombreEmpresaInstaladora);
        $('#responsable_email').val(email);
        $('#responsable_nombre').val(nombre);
        $('#responsable_telefono').val(telefono);

        getObrasPorEmpresa(idEmpresaInstaladora);


    });


    $('#obraOnsite').on('change', function () {
        idObra = $(this).val();
        getObraOnsite(idObra);

    });

    $('#obra_id').on('change', function () {
        idObra = $(this).val();
        obrasConSistemas(idObra);

    });


    


    $('#next-btn').on('click', function () {
        /* chequeo si es el último step del wizard form */
        segment = $(location).attr('href');
        largo = parseInt($(location).attr('href').length) - 1;
        console.log(segment[largo]);
        $('#next-btn').show();

        if (segment[largo] == 1) {
          
        }

        if (segment[largo] == 2) {

            let tarea = $('#tarea').val();
            let tarea_detalle = $('#tarea_detalle').val();
            let tipo_servicio = $('#id_tipo_servicio').val();
            let id_estado = $('#id_estado').val();
            let prioridad = $('#prioridad').val();

            if(!tarea || !tarea_detalle || !tipo_servicio || !id_estado || !prioridad) {
                showToast('Por favor, complete la información de la tarea', '', 'error');
            }

        }        

        if (segment[largo] == 3) {
            $('#next-btn').hide();
            validateForm();
        }
        

    });

    $('#prev-btn').on('click', function () {
        $('#next-btn').show();
    });

    $('.nav-item').on('click', function () {
        $('#next-btn').show();
    });

    $('#reset-btn').on('click', function () {
        $('input[type=text]').val('');
        $('input[type=email]').val('');
        $('input[type=number]').val('');
        $('.multiselect-dropdown').val('');
        $('.multiselect-dropdown').trigger('change');
        $('#next-btn').prop('disabled', false);

    });

    $('#sistema_onsite_id').on('change', function () {

        sistemasConSolicitudes($('#sistema_onsite_id').val());
        
        /* completo sistemas seleccionados */
        $('#tbody_sistemas_seleccionados_solicitud').html('');
        $('#sistemas_seleccionados_solicitud').prop('hidden', false);

        $('#sistema_onsite_id option:selected').each(function () {
            idSistema = $(this).val();
            nombreSistema = $(this).text();
            idObra = $(this).data('idobra');
           
            insertSistemasSeleccionadosSolicitud(idSistema, nombreSistema);
        });

    });


    $('#boton_enviar').on('click', function (e) {
        e.preventDefault();
        idTemplate = 6;

        $("#modalConfirmacion").modal('toggle');
        $('.bodymodalConfirmacion').html('');
        $('.bodymodalConfirmacion').html('Confirme el envío');

       /*  getTemplate(idTemplate).then(detalle => {
            $("#modalConfirmacion").modal('toggle');
            $('.bodymodalConfirmacion').html('');
            $('.bodymodalConfirmacion').html(detalle.cuerpo);

        }).catch(error => {
            console.log('Error al procesar la petición. TRACE: ');
            console.log(error.responseJSON.trace);
            showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
        }); */


    });

    $('#aceptarmodalConfirmacion').on('click', function () {

        loader = makeLoader('Procesando...');
        $('.bodymodalConfirmacion').html('');
        $('.bodymodalConfirmacion').html(loader);
        $('#aceptarmodalConfirmacion').hide();
        $('#cerrarmodalConfirmacion').hide();

        $('#sistema_onsite_id option:selected').each(function () {
            idSistema = $(this).val();
            nombreSistema = $(this).text();
            idObra = $(this).data('idobra');

            storeVisita(idSistema);
        });


    });

    $('#cerrarmodalConfirmacion').on('click', function () {

        $("#modalConfirmacion").modal('toggle');

    });



});


function resetSmartWizard() {


    $('#smartwizard').smartWizard("reset");
    return true;

}


function validateForm() {

    /* evalúa la cantidad de sistemas seleccionados */
    sistemasSeleccionados = 0;

    $('#sistema_onsite_id option:selected').each(function () {
        sistemasSeleccionados += 1;
    });

    let tarea = $('#tarea').val();
    let tarea_detalle = $('#tarea_detalle').val();
    let tipo_servicio = $('#id_tipo_servicio').val();
    let id_estado = $('#id_estado').val();
    let prioridad = $('#prioridad').val();

    if (sistemasSeleccionados > 0 && tarea && tarea_detalle && tipo_servicio && id_estado && prioridad) {
        $('.swal2-error').attr("hidden", true);
        $('.swal2-success').attr("hidden", false);
        $('#boton_enviar').removeClass('btn-warning');
        $('#boton_enviar').addClass('btn-success');
        $('#boton_enviar').attr("disabled", false);

        showResumen();
    }

    else if(!tarea || !tarea_detalle || !tipo_servicio || !id_estado || !prioridad) {

        $('#boton_enviar').removeClass('btn-success');
        $('#boton_enviar').addClass('btn-warning');

        $('#boton_enviar').attr("disabled", true);
        $('.swal2-error').attr("hidden", false);
        $('.swal2-success').attr("hidden", true);

        $('.resumen_form').html(
            '<span>Complete la información de la tarea</span>'
        );        
    }

    else {

        $('#boton_enviar').removeClass('btn-success');
        $('#boton_enviar').addClass('btn-warning');

        $('#boton_enviar').attr("disabled", true);
        $('.swal2-error').attr("hidden", false);
        $('.swal2-success').attr("hidden", true);

        $('.resumen_form').html(
            '<span>Seleccione Obra y Sistema a Inspeccionar</span>'
        );
    }
}

function showResumen() {
    let sistemas = '';
    $('#sistema_onsite_id option:selected').each(function () {
                
                    nombreSistema = $(this).text();
                    sistemas += '<p>'+ nombreSistema + '</p>'; 
            });
    

    $('.resumen_form').html(
        '<span>'
        + 'Empresa Instaladora: '
        + $("#empresa_instaladora_nombre").val()
        + '<br>'


        + 'Sistemas: '
        +'<br>'
        + sistemas
        + '<br>'

        + '</span>'
    );
}


function insertSistemasSeleccionadosSolicitud(idSistema, nombreSistema) {
    obsInternas = makeTextArea('observaciones_internas observacionesIdSistema-' + idSistema);
    notaCliente = makeTextArea('nota_cliente notasIdSistema-' + idSistema);
    sistemaSeleccionado = makeLabel(idSistema, nombreSistema);

    $('#tbody_sistemas_seleccionados_solicitud').append(
        sistemaSeleccionado
        + obsInternas
        + notaCliente
    );


}


function getObrasPorEmpresa(idEmpresa) {
    var rutaModelos = "/getObrasPorEmpresa/" + idEmpresa;


    $.get(rutaModelos, function (response) {

        $("#sistema_onsite_id").html('');
        $("#obra_id").html('');


        if (response.length <= 0)
            $("#sistema_onsite_id").append("<option selected='selected' value=''>Modelos no encontradas</option>");
        else {

            $("#obra_id").html('');
            for (i = 0; i < response.length; i++) {
                $("#sistema_onsite_id").append("<optgroup label='Obra: " + response[i].nombre + "' >");
                
                $("#obra_id").append("<option value='"
                + response[i].id+ "' >"
                + response[i].nombre
                +"</option>"
                );


                if (response[i].sistema_onsite.length > 0) {

                    for (j = 0; j < response[i].sistema_onsite.length; j++) {
                        $("#sistema_onsite_id").append(
                            "<option value="
                            + response[i].sistema_onsite[j].id
                            + " data-idobra="
                            + response[i].sistema_onsite[j].obra_onsite_id
                            + " data-nombre_sistema='"
                            + response[i].sistema_onsite[j].nombre
                            + "'>"
                            + response[i].sistema_onsite[j].nombre
                            + '<small> (Obra: '
                            + response[i].nombre
                            + ')</small>'
                            + "</option>");
                    }
                }
            };

        };


    });
}














$(function () {

    var crearnuevo = 0;

    let dataset = [];
    let dataset1 = [];

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

        if (segment[largo] == 1) {

            var time = 300;

            $('#sistema_onsite_id option:selected').each(function () {
                setTimeout(() => {
                    nombreSistema = $(this).text();
                    idSistema = $(this).val();
                    idObra = $(this).data('idobra');
                    getBouchers(idObra, idSistema, nombreSistema, dataset, dataset1);
                }, time);

                time += 300;
            });
            
            
            /* esto es para saltear la pantalla rapido */
            $('#smartwizard').smartWizard("next");
            /* $('#prev-btn').hide(); */
            /* $(this).hide(); */
            validateForm();
             /* ******************** */
        }

        if (segment[largo] == 2) {
            /* funciones para boucher */

            dataset.map(function (value) {
                console.log(value);
                sistemaId = value[6];
                boucherId = value[0];
                updateBoucher(boucherId, sistemaId);
            });


            let i = 0;
            let timeStore = 0;
            $('#tableSinBouchers  > tbody > tr > td > .precio').each(function () {
                setTimeout(() => {
                    precio = $(this).val();
                    sistemaId = dataset1[i][1];
                    obraId = dataset1[i][2];
                    codigo = 'BA-' + 'E' + $('#empresa_instaladora_admins').val() + 'O' + obraId + 'N';
                    idTarifa = $('#solicitud_tipo_id option:selected').data('idtarifa');
                    let fecha = new Date();
                    fecha1 = fecha.toISOString().split('T')[0];

                    storeBoucher(sistemaId, obraId, precio, codigo, idTarifa, fecha1);
                    i++;
                }, timeStore += 300);

            });



             $(this).hide();
            validateForm();
        }
        /*  $('#smartwizard').smartWizard("next"); */

    });

    $('#prev-btn').on('click', function () {
        $('#next-btn').show();
        segment = $(location).attr('href');
        largo = parseInt($(location).attr('href').length) - 1;
        console.log(segment[largo]);

        /* esto es para saltear bouchers */
        if (segment[largo] == 3) {
            $('#smartwizard').smartWizard("prev");
        }

        /* esto es para saltear bouchres */
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


        /* completo sistemas seleccionados */
        $('#tbody_sistemas_seleccionados_solicitud').html('');
        $('#sistemas_seleccionados_solicitud').prop('hidden', false);
        let dataset = [];

        $('#sistema_onsite_id option:selected').each(function () {
            idSistema = $(this).val();
            nombreSistema = $(this).text();
            idObra = $(this).data('idobra');
            console.log(idObra);

            insertSistemasSeleccionadosSolicitud(idSistema, nombreSistema);



        });

    });


    $('#boton_enviar').on('click', function (e) {
        e.preventDefault();
        idTemplate = 6;

        getTemplate(idTemplate).then(detalle => {
            $("#modalConfirmacion").modal('toggle');
            $('.bodymodalConfirmacion').html('');
            $('.bodymodalConfirmacion').html(detalle);

        }).catch(error => {
            console.log('Error al procesar la petición. TRACE: ');
            console.log(error.responseJSON.trace);
            showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
        });


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

            storeSolicitud(idSistema);
        });

        setTimeout(function () {
            $("#modalConfirmacion").modal('toggle');
            window.location.href = "/solicitudesOnsite/";
        }, 2500);

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

    if (sistemasSeleccionados > 0) {
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


function completeDataBouchers(dataSet, campos, destino) {

    if ($.fn.dataTable.isDataTable(destino)) {
        console.log('destroy');
        destroy = $(destino).DataTable().destroy();        
    }

    var table = $(destino).DataTable({
        data: dataSet,
        columns: campos,
        dom: 'Bfrtip',
        "order": [[0, "asc"]]

    });
    /* table.column(1).visible(false);
    table.column(5).visible(false);
    table.column(6).visible(false); */


    /* $('.add_button').prop("disabled", true); */

}


function getBouchers(idObra, idSistema, nombreSistema, dataset, dataset1) {

    getBouchersPorObra(idObra, idSistema, nombreSistema, dataset, dataset1).then(detalle => {

        if (detalle.id) {
            campos = [
                { title: "id" },
                { title: "Obra" },
                { title: "Sistema a Aplicar" },
                { title: "Cod." },
                { title: "Precio" },
                { title: "Obra ID" },
                { title: "Sistema ID" },
            ];

            destino = '#tableBouchers';
            dataset.push([detalle.id, detalle.obra_onsite.nombre, '[' + idSistema + '] - ' + nombreSistema, detalle.codigo, detalle.precio, detalle.obra_id, parseInt(idSistema)]);
            completeDataBouchers(dataset, campos, destino);
            return dataset;
        }

        else {
            campos1 = [
                { title: "Sistema a Aplicar" },
                { title: "idSistema" },
                { title: "idObra" },
                { title: "Precio" },
            ];

            destino1 = '#tableSinBouchers';
            /* consulta la base solicitures_Tarifas_Bases y trae la última versión */
            idTipoSolicitud = $('#solicitud_tipo_id').val();
            console.log(idTipoSolicitud);

            getTarifaSolicitudPorObra(idTipoSolicitud, idObra).then(respuesta => {

                if (respuesta.precio) {
                    console.log(respuesta);
                    precio = respuesta.precio;
                    console.log(precio);
                }
                else
                    precio = 0;

                input = makeInput('form-control precio', 'precio', precio);
                dataset1.push(['[' + idSistema + '] - ' + nombreSistema, parseInt(idSistema), parseInt(idObra), input]);
                completeDataBouchers(dataset1, campos1, destino1);

                $('#div_sin_boucher').html('');
                $('#div_sin_boucher').html('<span class="badge badge-warning col-12">ATENCION NO DISPONE DE BOUCHER. PROCEDE A CREAR NUEVOS CON COSTO</p>');

                return dataset1;

            }).catch(error => {
                showToast('Error en la petición revise consola', '', 'error');
                console.log(error.responseJSON.trace);
            });
console.log(dataset1);

        }

    }).catch(error => {
        console.log('Error al procesar. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR. Vea la consola para rastrear la causa', '', 'error');
    });
}











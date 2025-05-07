$(function () {

    $('form input').on('keydown', (function (e) {
        if (e.keyCode == 13 || e.keyCode == 10) {
            e.preventDefault();
            return false;
        }
    }));

    
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

    $(document).on('change', '.file-input', function () {


        var filesCount = $(this)[0].files.length;

        var textbox = $(this).prev();

        if (filesCount === 1) {
            var fileName = $(this).val().split('\\').pop();
            textbox.text(fileName);
        } else {
            textbox.text(filesCount + ' files selected');
        }
    });

    
    /* muestro provincia y localidad segun el pais */
    pais = $('#select_pais').val();
    showLocalidades(pais);

    /* empresa onsite */

    idEmpresaInstaladora = $('#empresa_instaladora_id').val();
    if (idEmpresaInstaladora > 0) getEmpresaOnsite();

    $('#empresa_onsite_id').on('change', function () {
        id_empresa_onsite = $(this).val();
        getSucursalesOnsite(id_empresa_onsite);
    });

    $('#empresa_instaladora_admins').on('change', function () {
        idEmpresaInstaladora = $(this).find(':selected').val();
        nombreEmpresaInstaladora = $(this).find(':selected').text();
        email = $(this).find(':selected').data('email');
        nombre = $(this).find(':selected').data('nombre');
        telefono = $(this).find(':selected').data('telefono');
        numero = $(this).find(':selected').data('numero');

        $('#empresa_instaladora_id').val(idEmpresaInstaladora);
        $('#empresa_instaladora_nombre').val(nombreEmpresaInstaladora);
        $('#responsable_email').val(email);
        $('#responsable_nombre').val(nombre);
        $('#responsable_telefono').val(telefono);

        if (numero > 0)
            $('#nro_cliente_bgh_ecosmart').val(numero);

        else
            $('#nro_cliente_bgh_ecosmart').val(99);




        getEmpresaOnsiteAdmins();
    });


    $('#boton_enviar').on('click', function (e) {

        e.preventDefault();
        $("#modalConfirmacion").modal('toggle');
        loader = makeLoader('Procesando...');
        $('.bodymodalConfirmacion').html(loader);

        setTimeout(function () {
            $("#modalConfirmacion").modal('toggle');
            window.location.href = "/obrasOnsite/";
        }, 2000);
    });

    /* UNIDADES EXTERNAS */
    $('.sistemas_creados').on('click', '.createUE', function (e) {
        e.preventDefault();
        idSistema = $(this).data('idsistema');
        idObra = $('#obra_onsite_id').val();
        nombreobra = $('#obra_nombre').val();
        nombreSistema = $(this).data('nombresistema');

        $("#sistema_onsite_id_unidades").append(
            "<option value="
            + idSistema
            + " data-idobra="
            + idObra
            + " data-nombre_sistema='"
            + nombreSistema
            + "'>"
            + nombreSistema
            + '<small> (Obra: '
            + nombreobra
            + ')</small>'
            + "</option>");

        $("#modalUE").modal('toggle');
        setTimeout(() => {

            $('#sistema_onsite_id_unidades').val(idSistema);
            $('#sistema_onsite_id_unidades').val(idSistema);
            $('#sistema_onsite_id_unidades').trigger('change');
            $('#sistema_onsite_id_unidades').prop('disabled', true);
        }, 500);


    });

    $('#guardarModalUE').on('click', function (e) {
        e.preventDefault();
        storeUnidadExterior();
    });

    $('#cerrarModalUE').on('click', function (e) {
        e.preventDefault();
        $("#modalUE").modal('toggle');

    });

    /* UNIDADES INTERNAS */
    $('.sistemas_creados').on('click', '.createUI', function (e) {
        e.preventDefault();
        idSistema = $(this).data('idsistema');
        idObra = $('#obra_onsite_id').val();
        nombreobra = $('#obra_nombre').val();
        nombreSistema = $(this).data('nombresistema');

        $("#sistema_onsite_id_unidades_interiores").append(
            "<option value="
            + idSistema
            + " data-idobra="
            + idObra
            + " data-nombre_sistema='"
            + nombreSistema
            + "'>"
            + nombreSistema
            + '<small> (Obra: '
            + nombreobra
            + ')</small>'
            + "</option>");

        $("#modalUI").modal('toggle');
        setTimeout(() => {

            $('#sistema_onsite_id_unidades_interiores').val(idSistema);
            $('#sistema_onsite_id_unidades_interiores').trigger('change');
            $('#sistema_onsite_id_unidades').prop('disabled', true);
        }, 500);


    });

    $('#guardarModalUI').on('click', function (e) {
        e.preventDefault();
        storeUnidadInterior();
    });

    $('#cerrarModalUI').on('click', function (e) {
        e.preventDefault();
        $("#modalUI").modal('toggle');

    });

    /* comprador */
    $('.sistemas_creados').on('click', '.createComprador', function (e) {
        e.preventDefault();
        idSistema = $(this).data('idsistema');
        $('#sistema_onsite_id_comprador').val(idSistema);

        idObra = $('#obra_onsite_id').val();
        nombreobra = $('#obra_nombre').val();
        nombreSistema = $(this).data('nombresistema');

        $("#CompradorModal").modal('toggle');

    });

    $('#guardarCompradorModal').on('click', function (e) {
        e.preventDefault();
        storeComprador();
    });

    $('#cerrarCompradorModal').on('click', function (e) {
        e.preventDefault();
        $("#CompradorModal").modal('toggle');
    });



    $('#select_pais').on('change', function () {
        pais = $(this).val();
        showLocalidades(pais);
    })


    $('#provincia').on('change', function () {
        idProvincia = $(this).val();
        getLocalidades(idProvincia);
    });

    $('#botonGuardarSistema').on('click', function (e) {
        e.preventDefault();
        storeSistema();
    });

    $('#store_obra').on('click', function (e) {
        e.preventDefault();
        storeObra();
    });

    $('#store_checklist').on('click', function (e) {
        e.preventDefault();
        storeCheckList();
    });

    $('#finalizar_carga').on('click', function (e) {
        e.preventDefault();
        $(this).prop('disabled', true);

        obra_onsite_id = $('#obra_onsite_id').val();
        blockDivByClass('sistemas', 300000);
        blockDivByClass('sistemas_unidades_creados', 300000);
        getObraOnsiteWithSistema(obra_onsite_id);
        $('#smartwizard').smartWizard("next");
    });
   

    $('#sistema_onsite_id').on('change', function () {
        sistemaid = $(this).find(':selected').val();
        idObra = $(this).find(':selected').data('idobra');
        nombreSistema = $(this).find(':selected').data('nombre_sistema');
        $('#obra_onsite_id').val(idObra);
        $('#obra_nombre').val(nombreSistema);
        var idEmpresaOnsite = $("#empresa_onsite_id").val();

        /* getObraOnsite(idObra); */
        limpiarSucursal();
        getSucursalesOnsite(idEmpresaOnsite);

    });

    $('#requiere_art').on('change', function () {
        check = $('#requiere_art').prop('checked');
        if (check == true)
            $('#div_art').prop('hidden', false);

        else
            $('#div_art').prop('hidden', true);
        $('#clausula_no_arrepentimiento').attr('checked', false);

    });

    $('#clausula_no_arrepentimiento').on('change', function () {
        check = $('#clausula_no_arrepentimiento').prop('checked');
        if (check == true)
            $('.div_cnr').prop('hidden', false);
        else
            $('.div_cnr').prop('hidden', true);
    });



});


function resetSmartWizard() {
    $('#smartwizard').smartWizard("reset");
    return true;
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
            $("#localidad").append("<option value='" + response[i].id + "'> " + response[i].localidad + "</option>");
        }



    });
}

function showLocalidades(pais) {
    $('#localidad_div').html('');

    if (pais == 'Argentina') {
        $('#provincia_div').removeAttr('hidden');

        $('#localidad_div').html(
            '<label>Localidad</label>'
            + '<select name="localidad" id="localidad" class="form-control multiselect-dropdown  mb-3">'
            + '</select> '
        );


        $('#localidad').trigger('change');

    }

    else {
        $('#provincia_div').prop('hidden', 'hidden');
        $('#localidad_div').html(
            '<label>Localidad Extranjero</label>'
            + '<input type="text" name="localidad_texto" id="localidad" class="form-control  mb-3">'
            + '</input> '
            + '<input type="hidden" name="localidad" value = "26"></input>'

        );

        $('#provincia_onsite_id').val()

    }
}


function getSucursalesOnsite(idEmpresaOnsite) {

    var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite;

    $.get(route, function (response, state) {
        console.log(response);
        limpiarSucursal(); // al buscar, antes, limpio selects y deshabilito botones

        if (response.length <= 0)
            $("#sucursal_onsite_id").append("<option selected='selected' value=1>DEFAULT</option>");

        if (response.length > 0) {

            for (i = 0; i < response.length; i++) {
                $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] " + "</option>");
            }

        }
        $("#sucursal_onsite_id").prop('disabled', true);
    });
}
function limpiarSucursal() {
    $("#sucursal_onsite_id").empty();
}



function storeSistema() {

    empresa_onsite_id = $('#empresa_onsite_id').val();
    sucursal_onsite_id = $('#sucursal_onsite_id').val();
    obra_onsite_id = $('#obra_onsite_id').val();
    sistema_nombre = $('#sistema_nombre').val();
    comentarios = $('#comentarios').val();
    fecha_compra = $('#fecha_compra').val();
    numero_factura = $('#numero_factura').val();



    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/storeSistema',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            empresa_onsite_id: empresa_onsite_id,
            sucursal_onsite_id: sucursal_onsite_id,
            obra_onsite_id: obra_onsite_id,
            nombre: sistema_nombre,
            comentarios: comentarios,
            fecha_compra: fecha_compra,
            numero_factura: numero_factura
        },
        dataType: 'JSON',
        success: function (data) {
            showToast('Sistema creado correctamente: ' + data.id, '', 'success');
            $('#next-btn').prop('disabled', false);
            $('#comentarios').val('');
            $('#sistema_nombre').val('');

            getSistemasPorObra(obra_onsite_id);

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

function storeObra() {

    dataForm = setDataStoreObra();

    $.ajax({
        url: '/storeObra',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Obra creada correctamente: ' + data.id, '', 'success');
            $('#obra_onsite_id').val(data.id);
            $('#obra_nombre').val(data.nombre);
            idEmpresaOnsite = data.empresa_onsite_id;


            $('#store_obra').prop('disabled', true);
            blockDivByClass('empresa_obra', 300000);
            blockDivByClass('empresa_obra1', 300000);
            $('#smartwizard').smartWizard("next");
            limpiarSucursal();
            getSucursalesOnsite(idEmpresaOnsite);
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

function storeCheckList() {
    setCheckList();

    if ($('#vip').is(":checked")) vip = 1;
    if ($('#requiere_zapatos_seguridad').is(":checked")) requiere_zapatos_seguridad = 1;
    if ($('#requiere_casco_seguridad').is(":checked")) requiere_casco_seguridad = 1;
    if ($('#requiere_proteccion_visual').is(":checked")) requiere_proteccion_visual = 1;
    if ($('#requiere_proteccion_auditiva').is(":checked")) requiere_proteccion_auditiva = 1;
    if ($('#requiere_art').is(":checked")) requiere_art = 1;
    if ($('#clausula_no_arrepentimiento').is(":checked")) clausula_no_arrepentimiento = 1;


    cuit = $('#cuit').val();
    razon_social = $('#razon_social').val();
    cnr_detalle = $('#cnr_detalle').val();
    obra_onsite_id = $('#obra_onsite_id').val();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/storeCheckList',
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            requiere_zapatos_seguridad: requiere_zapatos_seguridad,
            requiere_casco_seguridad: requiere_casco_seguridad,
            requiere_proteccion_visual: requiere_proteccion_visual,
            requiere_art: requiere_art,
            requiere_proteccion_auditiva: requiere_proteccion_auditiva,
            clausula_no_arrepentimiento: clausula_no_arrepentimiento,
            cuit: cuit,
            razon_social: razon_social,
            cnr_detalle: cnr_detalle,
            company_id: 2,
            obra_onsite_id: obra_onsite_id,
            vip: vip

        },
        dataType: 'JSON',
        success: function (data) {
            showToast('Check list creado correctamente: ' + data.id, '', 'success');

            $('#store_checklist').prop('disabled', true);
            blockDivByClass('checklist', 300000);
            blockDivByClass('div_art', 300000);

            $('#smartwizard').smartWizard("next");

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

function setCheckList() {
    requiere_zapatos_seguridad = 0;
    requiere_casco_seguridad = 0;
    requiere_proteccion_visual = 0;
    requiere_proteccion_auditiva = 0;
    requiere_art = 0;
    clausula_no_arrepentimiento = 0;
    vip = 0;
}


function getSistemasPorObra(idObra) {

    $("#sistemas_creados").html('');
    $('#sistemas_creados').append();

    $.ajax({
        url: '/getSistemasPorObra/' + idObra,
        type: 'GET',
        success: function (data) {
            showToast('Se listan los sistemas de la Obra: ' + idObra, '', 'success');
            $('#sistemas_unidades_creados').prop('hidden', false);

            tabla_sistemas = '';

            for (let index = 0; index < data.length; index++) {

                if (index == 0) primer_fila = '<table class="table table-striped">'
                    + '<tr> </tr><th>id</th><th>nombre</th><th>UI</th><th>UE</th> </tr>';
                else primer_fila = '';
                tabla_sistemas +=
                    primer_fila
                    + '<tr><td>'
                    + data[index].id
                    + '</td>'
                    + '<td>'
                    + data[index].nombre
                    + '</td>'
                    + '<td>'
                    /* + '<a target="_blank"  class="btn btn-success" href="insertUISistema/' */
                    + '<button id="createUI" class="createUI btn btn-success" data-idsistema='
                    + data[index].id
                    + ' data-nombresistema="'
                    + data[index].nombre
                    + '">'
                    + 'Agregar UI</button>'
                    + '</td>'
                    + '<td>'
                    /* + '<a target="_blank" class="btn btn-success" href="insertUESistema/' */
                    + '<button id="createUE" class="createUE btn btn-success" data-idsistema='
                    + data[index].id

                    + ' data-nombresistema="'
                    + data[index].nombre
                    + '">'
                    + 'Agregar UE</button>'
                    + '</td>'

                    + '<td>'
                    + '<button id="createComprador" class="createComprador btn btn-success" data-idsistema='
                    + data[index].id
                    + ' data-nombresistema="'
                    + data[index].nombre
                    + '">'
                    + 'Crear Comprador</button>'
                    + '</td>'

                    + '</tr>'
            };

            $('#sistemas_creados').append(tabla_sistemas);

            $('#sistemas_creados').append('</table>');


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

    });
}

function getObraOnsiteWithSistema(idObra) {
    var rutaModelos = "/getObraOnsiteWithSistema/" + idObra;

    $.get(rutaModelos, function (response) {

        $('.resumen_form').html('');

        for (i = 0; i < response.length; i++) {
            $('.resumen_form').append('<br>');
            $('.resumen_form').append('OBRA: ' + response[i].nombre);
            $('.resumen_form').append('<br>');
            $('.resumen_form').append('<hr>');
            $('.resumen_form').append('SISTEMAS');
            $('.resumen_form').append('<br>');

            if (response[i].sistema_onsite.length > 0) {

                for (j = 0; j < response[i].sistema_onsite.length; j++) {

                    $('.resumen_form').append('[' + response[i].sistema_onsite[j].id + ']');
                    $('.resumen_form').append(' - ');
                    $('.resumen_form').append(response[i].sistema_onsite[j].nombre);
                    $('.resumen_form').append('<br>');

                }
            }
        };
    });
}

function setDataStoreObra() {

    empresa_instaladora_id = $('#empresa_instaladora_id').val();
    empresa_onsite_id = $('#empresa_onsite_id').val();
    responsable_email = $('#responsable_email').val();
    responsable_nombre = $('#responsable_nombre').val();
    responsable_telefono = $('#responsable_telefono').val();
    empresa_instaladora_nombre = $('#empresa_instaladora_nombre').val();
    nombre = $('#nombre').val();
    nro_cliente_bgh_ecosmart = $('#nro_cliente_bgh_ecosmart').val();
    select_pais = $('#select_pais').val();
    provincia = $('#provincia').val();
    localidad = $('#localidad').val();
    domicilio = $('#autocomplete').val();
    longitud = $('#longitude').val();
    latitud = $('#latitude').val();

    cantidad_unidades_exteriores = $('#cantidad_unidades_exteriores').val();
    cantidad_unidades_interiores = $('#cantidad_unidades_interiores').val();
    estado = $('#estado').val();
    estado_detalle = $('#estado_detalle').val();

    /* file = $('#esquema_archivo').prop('files')[0]; */
    var ins = document.getElementById('esquema_archivo').files.length;


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    /* ************************ */

    var dataForm = new FormData();

    for (var x = 0; x < ins; x++) {
        dataForm.append("esquema_archivo[]", document.getElementById('esquema_archivo').files[x]);
    }

    dataForm.append('_token', CSRF_TOKEN);
    dataForm.append('empresa_instaladora_id', empresa_instaladora_id);
    dataForm.append('empresa_onsite_id', empresa_onsite_id);
    dataForm.append('responsable_email', responsable_email);
    dataForm.append('nombre', nombre);
    dataForm.append('nro_cliente_bgh_ecosmart', nro_cliente_bgh_ecosmart);
    dataForm.append('pais', select_pais);
    dataForm.append('provincia_onsite_id', provincia);
    dataForm.append('localidad_onsite_id', localidad);
    dataForm.append('domicilio', domicilio);
    dataForm.append('longitud', longitud);
    dataForm.append('latitud', latitud);
    dataForm.append('cantidad_unidades_exteriores', cantidad_unidades_exteriores);
    dataForm.append('cantidad_unidades_interiores', cantidad_unidades_interiores);
    dataForm.append('empresa_instaladora_nombre', empresa_instaladora_nombre);
    dataForm.append('empresa_instaladora_responsable', responsable_nombre);
    dataForm.append('responsable_telefono', responsable_telefono);
    dataForm.append('estado', estado);
    dataForm.append('estado_detalle', estado_detalle);
    dataForm.append('localidad_texto', localidad);
    dataForm.append('clave', '1234claveprovisoria');

    /* dataForm.append('esquema_archivo', file); */

    return dataForm;
}

/* UNIDADES EXTERIORES */
function storeUnidadExterior() {

    dataForm = setDataUnidadExterior();

    $.ajax({
        url: '/storeUnidadExterior',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Unidad Exterior creada correctamente: ' + '[' + data.id + '] ' + data.clave, '', 'success');
            $("#modalUE").modal('toggle');
            $('.form_ue').val('');
            getUnidadesExterioresPorSistema(data.sistema_onsite_id);
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

function setDataUnidadExterior() {

    anclaje_piso = 0;
    contra_sifon = 0;
    mm_500_ultima_derivacion_curva = 0;
    if ($('#anclaje_piso').is(":checked")) anclaje_piso = 1;
    if ($('#contra_sifon').is(":checked")) contra_sifon = 1;
    if ($('#mm_500_ultima_derivacion_curva').is(":checked")) mm_500_ultima_derivacion_curva = 1;


    var dataForm = new FormData();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    dataForm.append('_token', CSRF_TOKEN);

    dataForm.append('clave', 1);
    dataForm.append('empresa_onsite_id', $('#empresa_onsite_id').val());
    dataForm.append('modelo', $('#modelo').val());
    dataForm.append('direccion', $('#direccion').val());
    dataForm.append('faja_garantia', $('#faja_garantia').val());
    dataForm.append('serie', $('#serie').val());
    dataForm.append('sistema_onsite_id', $('#sistema_onsite_id_unidades').val());
    dataForm.append('medida_figura_1_a', $('#medida_figura_1_a').val());
    dataForm.append('medida_figura_1_b', $('#medida_figura_1_b').val());
    dataForm.append('medida_figura_1_c', $('#medida_figura_1_c').val());
    dataForm.append('medida_figura_1_d', $('#medida_figura_1_d').val());
    dataForm.append('medida_figura_2_a', $('#medida_figura_2_a').val());
    dataForm.append('medida_figura_2_b', $('#medida_figura_2_b').val());
    dataForm.append('medida_figura_2_c', $('#medida_figura_2_c').val());

    dataForm.append('anclaje_piso', anclaje_piso);
    dataForm.append('contra_sifon', contra_sifon);
    dataForm.append('mm_500_ultima_derivacion_curva', mm_500_ultima_derivacion_curva);

    dataForm.append('observaciones', $('#observaciones').val());

    return dataForm;
}

function getUnidadesExterioresPorSistema(idSistema) {

    $("#unidades_exteriores_creadas").html('');
    $('#unidades_exteriores_creadas').append();

    $.ajax({
        url: '/getUnidadesExterioresPorSistema/' + idSistema,
        type: 'GET',
        success: function (data) {
            showToast('Se listan las unidades exteriores del Sistema: ' + idSistema, '', 'success');
            console.log(data);
            tabla_unidades_exteriores = '';

            for (let index = 0; index < data.length; index++) {
                console.log(data[index]);
                if (index == 0) primer_fila = '<table class="table table-striped">'
                    + '<tr> </tr><th>id</th><th>Sistema</th><th>Clave</th></tr>';
                else primer_fila = '';

                tabla_unidades_exteriores +=
                    primer_fila
                    + '<tr><td>'
                    + data[index].id
                    + '</td>'
                    + '<td>'
                    + data[index].sistema_onsite.nombre
                    + '</td>'
                    + '<td>'
                    + data[index].clave
                    + '</td>'
                    + '</tr>'
            };

            $('#unidades_exteriores_creadas').append(tabla_unidades_exteriores);

            $('#unidades_exteriores_creadas').append('</table>');

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

    });
}

/* UNIDADES INTERIORES */
function storeUnidadInterior() {

    dataForm = setDataUnidadInterior();

    $.ajax({
        url: '/storeUnidadInterior',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Unidad Interior creada correctamente: ' + '[' + data.id + '] ' + data.clave, '', 'success');
            $("#modalUI").modal('toggle');
            $('.form_ui').val('');
            getUnidadesInterioresPorSistema(data.sistema_onsite_id);
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

function setDataUnidadInterior() {

    var dataForm = new FormData();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    dataForm.append('_token', CSRF_TOKEN);

    dataForm.append('clave', 1);
    dataForm.append('empresa_onsite_id', $('#empresa_onsite_id').val());
    dataForm.append('modelo', $('#modelo_ui').val());
    dataForm.append('direccion', $('#direccion_ui').val());
    dataForm.append('faja_garantia', $('#faja_garantia_ui').val());
    dataForm.append('serie', $('#serie_ui').val());
    dataForm.append('sistema_onsite_id', $('#sistema_onsite_id_unidades_interiores').val());

    dataForm.append('observaciones', $('#observaciones_ui').val());

    return dataForm;
}

function getUnidadesInterioresPorSistema(idSistema) {

    $("#unidades_interiores_creadas").html('');
    $("#unidades_interiores_creadas").append('');


    $.ajax({
        url: '/getUnidadesInterioresPorSistema/' + idSistema,
        type: 'GET',
        success: function (data) {
            showToast('Se listan las unidades interiores del Sistema: ' + idSistema, '', 'success');

            tabla_unidades_interiores = '';

            for (let index = 0; index < data.length; index++) {

                if (index == 0) primer_fila = '<table class="table table-striped">'
                    + '<tr> </tr><th>id</th><th>Sistema</th><th>Clave</th></tr>';
                else primer_fila = '';

                tabla_unidades_interiores +=
                    primer_fila
                    + '<tr><td>'
                    + data[index].id
                    + '</td>'
                    + '<td>'
                    + data[index].sistema_onsite.nombre
                    + '</td>'
                    + '<td>'
                    + data[index].clave
                    + '</td>'
                    + '</tr>'
            };

            $('#unidades_interiores_creadas').append(tabla_unidades_interiores);

            $('#unidades_interiores_creadas').append('</table>');

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

    });
}

/* empresa onsite */

function getEmpresasOnsitePorInstaladora() {

    return $.ajax({
        url: "/getEmpresasOnsitePorInstaladora/",
        type: 'GET',
    }
    );
}

function getEmpresasOnsitePorInstaladoraId(idEmpresaInstaladora) {

    return $.ajax({
        url: "/getEmpresasOnsitePorInstaladoraId/" + idEmpresaInstaladora,
        type: 'GET',
    }
    );
}

function storeEmpresaOnsite(esAdmin) {

    dataForm = setDataEmpresaOnsite();

    $.ajax({
        url: '/storeEmpresaOnsite',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Se ha creado Empresa Onsite correctamente: ' + '[' + data.id + '] ' + data.clave, '', 'success');
            if (esAdmin)
                getEmpresaOnsiteAdmins();
            else
                getEmpresaOnsite();
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

function setDataEmpresaOnsite() {

    var dataForm = new FormData();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    dataForm.append('_token', CSRF_TOKEN);

    clave = $('#empresa_instaladora_id').val() + $('#empresa_instaladora_nombre').val();

    dataForm.append('clave', clave);
    dataForm.append('nombre', clave);
    dataForm.append('pais', 'Argentina');
    dataForm.append('provincia_onsite_id', 26);
    dataForm.append('localidad_onsite_id', 1);
    dataForm.append('email_responsable', $('#responsable_email').val());
    dataForm.append('requiere_tipo_conexion_local', 1);
    dataForm.append('generar_clave_reparacion', 1);
    dataForm.append('empresa_instaladora_id', $('#empresa_instaladora_id').val());


    return dataForm;
}

function getEmpresaOnsite() {
    getEmpresasOnsitePorInstaladora().then(detalle => {

        var size = Object.keys(detalle).length;

        if (size > 0) {
            for (let key in detalle) {
                console.log(detalle[key]);
                $("#empresa_onsite_id").append("<option value='" + detalle[key].id + "'> " + nombre + "</option>");
            }
            $("#empresa_onsite_id").prop('disabled', true);
        }

        else {
            storeEmpresaOnsite(false);
        };

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });
}

function getEmpresaOnsiteAdmins() {

    idEmpresaInstaladora = $('#empresa_instaladora_id').val();
    getEmpresasOnsitePorInstaladoraId(idEmpresaInstaladora).then(detalle => {

        var size = Object.keys(detalle).length;

        if (size > 0) {
            $("#empresa_onsite_id").html('');

            for (let key in detalle) {

                $("#empresa_onsite_id").append("<option value='" + detalle[key].id + "'> " + detalle[key].nombre + "</option>");
            }
            $("#empresa_onsite_id").prop('disabled', true);
        }

        else {

            storeEmpresaOnsite(true);
        };

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });
}

/* COMPRADOR ONSITE */

function storeComprador() {

    dataForm = setDataComprador();

    $.ajax({
        url: '/storeComprador',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Comprador creado correctamente: ' + '[' + data.id + '] ' + data.nombre, '', 'success');
            $("#CompradorModal").modal('toggle');
            $('.form_comprador').val('');

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

function setDataComprador() {

    var dataForm = new FormData();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    dataForm.append('_token', CSRF_TOKEN);

    dataForm.append('dni', $('#dni').val());
    dataForm.append('primer_nombre', $('#primer_nombre').val());

    dataForm.append('nombre', $('#nombre_comprador').val());
    dataForm.append('apellido', $('#apellido').val());
    dataForm.append('pais', $('#select_pais').val());
    dataForm.append('provincia_onsite_id', $('#provincia').val());
    dataForm.append('localidad_onsite_id', $('#localidad').val());
    dataForm.append('domicilio', $('#domicilio').val());
    dataForm.append('email', $('#email').val());
    dataForm.append('celular', $('#celular').val());

    dataForm.append('sistema_onsite_id', $('#sistema_onsite_id_comprador').val());

    return dataForm;
}
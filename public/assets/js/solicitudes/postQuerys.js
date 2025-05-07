function storeSolicitud(idSistema) {
    dataForm = setDataStoreSolicitud(idSistema);

    $.ajax({
        url: '/storeSolicitud',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            $("#modalConfirmacion").modal('toggle');
            showToast('Registro creado correctamente: ' + data.id, '', 'success');


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

function setDataStoreSolicitud(idSistema) {

    idTipoSolicitud = $('#solicitud_tipo_id').val();

    observaciones = $('#tbody_sistemas_seleccionados_solicitud .observacionesIdSistema-' + idSistema).val();
    nota_cliente = $('#tbody_sistemas_seleccionados_solicitud .notasIdSistema-' + idSistema).val();

    idEmpresa = $('#empresa_instaladora_id').val();
    idUser = $('#user_id').val();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    /* ************************ */

    var dataForm = new FormData();
    dataForm.append('_token', CSRF_TOKEN);
    /* dataForm.append('obra_onsite_id', idObra); */
    dataForm.append('sistema_onsite_id', idSistema);
    dataForm.append('estado_solicitud_onsite_id', 1);
    dataForm.append('solicitud_tipo_id', idTipoSolicitud);
    dataForm.append('observaciones_internas', observaciones);
    dataForm.append('nota_cliente', nota_cliente);
    dataForm.append('empresa_instaladora_id', idEmpresa);
    dataForm.append('user_id', idUser);

    return dataForm;
}


function updateBoucher(boucherId, sistemaId) {
    dataForm = setDataUpdateBoucher(sistemaId);

    $.ajax({
        url: '/updateBoucher/' + boucherId,
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Registro actualizado correctamente: ' + data.id, '', 'success');
            console.log('actualizados...');
            console.log(data);

            unsetSessionVariable().then(resultado => {
                console.log(resultado);
            }).catch(error => {
                showToast('no es posible borrar variable de sesión', '', 'error');
                console.log(error.responseJSON.trace)
            });


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

function setDataUpdateBoucher(sistemaId) {


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    /* ************************ */

    var dataForm = new FormData();
    dataForm.append('_token', CSRF_TOKEN);

    dataForm.append('consumido', 1);

    dataForm.append('sistema_id_consumido', sistemaId);
    dataForm.append('pendiente_imputacion', 1);

    dataForm.append('observaciones', 'Boucher Aplicado a Sistema id: ' + sistemaId);

    return dataForm;
}

function storeBoucher(sistemaId, obraId, precio, codigo, idTarifa, fecha) {
    dataForm = setDataStoreBoucher(sistemaId, obraId, precio, codigo, idTarifa, fecha);

    $.ajax({
        url: '/storeBoucher/',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Registro creado correctamente: ' + data.id, '', 'success');
            console.log('creados...');
            console.log(data);

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

function setDataStoreBoucher(sistemaId, obraId, precio, codigo, idTarifa, fecha) {


    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    /* ************************ */

    var dataForm = new FormData();
    dataForm.append('_token', CSRF_TOKEN);

    dataForm.append('obra_id', obraId);
    dataForm.append('precio', precio);

    dataForm.append('solicitud_tarifa_id', idTarifa);
    dataForm.append('codigo', codigo);
    dataForm.append('fecha_expira', fecha);
    dataForm.append('solicitud_boucher_tipo_id', 2);    //con costo
    dataForm.append('consumido', 1);

    dataForm.append('sistema_id_consumido', sistemaId);
    dataForm.append('pendiente_imputacion', 1);

    dataForm.append('observaciones', 'Boucher Aplicado a Sistema id: ' + sistemaId);

    return dataForm;
}
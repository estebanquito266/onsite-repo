function storeVisita(idSistema) {
    dataForm = setDataStoreVisita(idSistema);

    $.ajax({
        url: '/visitasOnsite',
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            $("#modalConfirmacion").modal('toggle');
            showToast('Registro creado correctamente: ' + data.reparacionOnsite.id, '', 'success');

            $("#modalConfirmacion").modal('toggle');
            window.location.href = "/visitasOnsite/";

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

function setDataStoreVisita(idSistema) {

    idTipoSolicitud = $('#solicitud_tipo_id').val();
    observaciones = $('#tbody_sistemas_seleccionados_solicitud .observacionesIdSistema-' + idSistema).val();
    nota_cliente = $('#tbody_sistemas_seleccionados_solicitud .notasIdSistema-' + idSistema).val();
    idEmpresa = $('#empresa_instaladora_id').val();
    idUser = $('#user_id').val();
    tarea = $('#tarea').val();
    tarea_detalle = $('#tarea_detalle').val();
    id_tipo_servicio = $('#id_tipo_servicio').val();
    solicitud_tipo_id = $('#solicitud_tipo_id').val();
    id_estado = $('#id_estado').val();
    id_tecnico_asignado = $('#id_tecnico_asignado').val();
    prioridad = $('#prioridad').val();
    solicitud = $('#solicitud_id').val();

    doc_link1 = $('#doc_link1').val();
    doc_link2 = $('#doc_link2').val();
    doc_link3 = $('#doc_link3').val();


    

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    /* ************************ */

    var dataForm = new FormData();
    dataForm.append('_token', CSRF_TOKEN);
    /* dataForm.append('obra_onsite_id', idObra); */
    dataForm.append('sistema_onsite_id', idSistema);
    dataForm.append('tarea', tarea);
    dataForm.append('tarea_detalle', tarea_detalle);
    dataForm.append('id_tipo_servicio', id_tipo_servicio);
    dataForm.append('solicitud_tipo_id', solicitud_tipo_id);
    dataForm.append('id_estado', id_estado);
    dataForm.append('id_tecnico_asignado', id_tecnico_asignado);
    dataForm.append('sucursal_onsite_id', 19142);
    dataForm.append('prioridad', prioridad);
    dataForm.append('observaciones_internas', observaciones);
    dataForm.append('nota_cliente', nota_cliente);
    dataForm.append('usuario_id', idUser);
    dataForm.append('reparacion_onsite_puesta_marcha_id', 1);
    dataForm.append('solicitud_tipo_id', idTipoSolicitud);
    dataForm.append('solicitud_id', solicitud);
    dataForm.append('id_empresa_onsite', 999);

    dataForm.append('doc_link1', doc_link1);
    dataForm.append('doc_link2', doc_link2);
    dataForm.append('doc_link3', doc_link3);


    

    return dataForm;
}
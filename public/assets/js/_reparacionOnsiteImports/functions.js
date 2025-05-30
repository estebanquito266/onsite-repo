function inicioFormularioSteps() {

    cargandoBgh = makeLoader('Cargando formulario y librerías');

    $('#cargando_bgh').html(cargandoBgh);
    setTimeout(function () {
        $('#cargando_bgh').html('');
    }, 2000
    );

    setTimeout(function () {
        resetSmartWizard();
        $('.formulario_obra').removeAttr('hidden');
    }, 3000
    );

}

function resetSmartWizard() {
    $('#smartwizard').smartWizard("reset");
    return true;
}

async function getRowsProcessedFunction(ultimo_id, ultima_dif) {


    return getRowsProcessed(ultimo_id, ultima_dif).then(response => {
        let calculateProcessed;

   
        console.log('Último registro: ' + response);
        console.log('ultimo_id: ' ,ultimo_id);
        console.log('ultima_dif: ' ,ultima_dif);
        if (ultimo_id > 0) {
            nueva_dif = parseInt(response - ultimo_id);
            console.log(nueva_dif,'>',ultima_dif,'=',(nueva_dif > ultima_dif));
            if (nueva_dif > ultima_dif) {
                showToast('Filas procesadas: ' + nueva_dif, '', 'info');
                calculateProcessed = setTimeout(() => {
                    getRowsProcessedFunction(response, nueva_dif);
                }, 6000);
            }
            else {
                //   clearTimeout(calculateProcessed);
                showToast('Procesando. Aguarde por favor', '', 'info');
                calculateProcessed = setTimeout(() => {
                    getRowsProcessedFunction(response, nueva_dif);
                }, 6000);
            }

            return calculateProcessed;
        }

    }).catch(error => {

        if (calculateProcessed && calculateProcessed !== undefined) {
            console.log('procede a detener');
            //clearTimeout(calculateProcessed);
        }

        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');

        return calculateProcessed;
    });


}

async function getLastIdProcessed() {

    return getRowsProcessed().then(response => {
        console.log('primer consulta...' + response);
        return response;

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });


}

function getReparacionesRecepcionarFunction() {

    getReparacionesRecepcionar().then(data => {

        let dataset = [];
        console.log(data.length);
        for (let index = 0; index < data.data.length; index++) {
            /* button = makeButton('button', 'sistema_button', data[index].id, data[index].nombre, 'btn btn-success sistema_button', 'Ver'); */

            let reparaciones = 0;

            /* for (let j = 0; j < data[index].sistema_onsite.length; j++) {
                reparaciones += data[index].sistema_onsite[j].reparacion_onsite.length
            } */

            dataset.push([
                data.data[index].id,
                data.data[index].id_cliente,
                data.data[index].id_equipo,
                data.data[index].falla_cliente,
                data.data[index].created_at,
                //reparaciones
            ]);

        };

        columns =
            [
                { title: "id" },
                { title: "cliente" },
                { title: "equipo" },
                { title: "falla" },
                { title: "fecha" },


            ]
            ;

        destino = 'table_listado_reparacion';

        console.log(data.data);
        console.log(dataset);
        completeDataTables(dataset, columns, destino);

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        console.log(error);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}

function processErrors(data) {
    if (data.responseJSON && data.responseJSON.message != "The GET method is not supported for this route. Supported methods: POST.") {
        $.each(data.responseJSON, function (index, item) {
            $('.bodymodalConfirmacion').append('<li>' + index + '-' + item + '</li>');

        });
    }
    else {
        $('.bodymodalConfirmacion').append('<li>' + 'Registro de archivo extenso correcto.' + '</li>');

    }
    if (data.responseJSON && data.responseJSON.errors) {
        $.each(data.responseJSON.errors[0], function (index, item) {
            $('.bodymodalConfirmacion').append('<li>' + index + '-' + item + '</li>');
        });
    }

    /* if (data.responseText) {
        console.log('text: ' + data.responseText);
        $('.bodymodalConfirmacion').append('<li>' + data.responseText + '</li>');
    } */

    /* if (data.responseJSON.message) {
        $('.bodymodalConfirmacion').append('<li>' + data.responseText + '</li>');
    } */
}



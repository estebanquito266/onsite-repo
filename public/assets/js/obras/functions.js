function inicioFormularioSteps() {
    
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
    
}

function resetSmartWizard() {
    $('#smartwizard').smartWizard("reset");
    return true;
}

function getSucursales(idEmpresaOnsite) {

    getSucursalesOnsite(idEmpresaOnsite).then(response => {

        $("#sucursal_onsite_id").empty();

        if (response.length <= 0)
            $("#sucursal_onsite_id").append("<option selected='selected' value=1>DEFAULT</option>");

        if (response.length > 0) {

            for (i = 0; i < response.length; i++) {
                $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] " + "</option>");
            }

        }
        $("#sucursal_onsite_id").prop('disabled', true);


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


function getUnidadesExterioresPorSistema(idSistema) {

    getUnidadesExteriores(idSistema).then(data => {

        $("#unidades_exteriores_creadas").html('');
        $('#unidades_exteriores_creadas').append();
        showToast('Se listan las unidades exteriores del Sistema: ' + idSistema, '', 'success');

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

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}


function getUnidadesInterioresPorSistema(idSistema) {

    getUnidadesInteriores(idSistema).then(data => {
        $("#unidades_interiores_creadas").html('');
        $("#unidades_interiores_creadas").append('');

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


    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
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

function getLocalidades(idProvincia) {

    getLocalidadesPorId(idProvincia).then(response => {
        $("#localidad").html('');

        if (response.length <= 0)
            $("#localidad").append("<option selected='selected' value=''>Localidad</option>");

        if (response.length > 1)
            $("#localidad").append("<option selected='selected' value=''>Seleccione la localidad onsite - </option>");

        for (i = 0; i < response.length; i++) {
            $("#localidad").append("<option value='" + response[i].id + "'> " + response[i].localidad + "</option>");
        }

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });   

 
}

function getSistemasPorObra(idObra) {

    getSistemasPorObraQuery(idObra).then(data => {
        $("#sistemas_creados").html('');
        $('#sistemas_creados').append();

        
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



    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    }); 
   

   
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

function getObraOnsiteWithSistema(idObra) {

    getObraOnsiteWithSistemaPorObra(idObra).then(response => {
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

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });    

    
}
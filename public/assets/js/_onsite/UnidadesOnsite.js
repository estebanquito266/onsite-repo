$(document).ready(function () {

    idEmpresa = $('#empresa_instaladora_id').val();
    getObrasPorEmpresa(idEmpresa);



    $("#empresa_onsite_id").change(function () { // p/buscar        
        limpiarSucursal();
        limpiarSistemas();
        getSucursalesOnsite();

    });

    $("#sucursal_onsite_id").click(function () { // p/buscar                
        console.log('sucursales...');
        limpiarSistemas();
        getSistemasOnsite();

    });


    $('.etiqueta').on('click', function () {
        idUnidadInterior = $(this).data('idunidadinterior');
        $('#unidad_interior_id').val(idUnidadInterior);
        getEtiquetasPorIdUnidadInterior(idUnidadInterior);

        $("#createEtiquetaModal").modal('toggle');
    });


    $('#guardarEtiquetaUI').on('click', function () {
        idUnidadInterior = $('#unidad_interior_id').val();
        storeEtiqueta(idUnidadInterior);

    });

    $('#cerrarEtiquetaUI').on('click', function () {
        $("#createEtiquetaModal").modal('toggle');

    });

    $('.filas_etiquetas').on('click', '.elimina_etiqueta', function () {
        idEtiqueta = $(this).data('idetiqueta');
        console.log(idEtiqueta);
        delEtiquetaPorId(idEtiqueta);        
    });


    $('#botonEliminar').on('click', function (event) {
        event.preventDefault();
        let title = $(this).data('modal_title');
        $('#modalConfirmacionTitle').html(title);
        $('.subtitleModalConfirmacion').html('Eliminar');
        $('.bodymodalConfirmacion').html('¿Desea eliminar esta unidad?');        
        $("#modalConfirmacion").modal('toggle');
    });

    $('#aceptarmodalConfirmacion').on('click', function (event) {
        $('#formEliminar').trigger("submit");
    });

    $('#cerrarmodalConfirmacion').on('click', function () {
        $("#modalConfirmacion").modal('toggle');
    });    
});

function getSucursalesOnsite() {

    var idEmpresaOnsite = $("#empresa_onsite_id").val();

    var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite;

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

function getSistemasOnsite() {
    var idSucursalOnsite = $("#sucursal_onsite_id").val();

    var route = "/buscarSistemasOnsite/" + idSucursalOnsite;

    $.get(route, function (response, state) {

        limpiarSistemas(); // al buscar, antes, limpio selects y deshabilito botones

        if (response.length <= 0)
            $("#sistema_onsite_id").append("<option selected='selected' value=''>Sistema Onsite no encontrada</option>");

        if (response.length > 1)
            $("#sistema_onsite_id").append("<option selected='selected' value=''>Seleccione la sistema onsite - </option>");

        for (i = 0; i < response.length; i++) {
            $("#sistema_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].nombre + "</option>");
        }

    });
}


function limpiarSucursal() {
    $("#sucursal_onsite_id").empty();
}

function limpiarSistemas() {
    $("#sistema_onsite_id").empty();
}


function getObrasPorEmpresa(idEmpresa) {
    var rutaModelos = "/getObrasPorEmpresa/" + idEmpresa;


    $.get(rutaModelos, function (response) {

        $("#sistema_onsite_id_unidades").html('');

        if (response.length <= 0)
            $("#sistema_onsite_id_unidades").append("<option selected='selected' value=''>Sistemas no encontradas</option>");
        else {
            $("#sistema_onsite_id_unidades").append("<option selected='selected' value=''>Seleccione el sistema - </option>");

            for (i = 0; i < response.length; i++) {
                $("#sistema_onsite_id_unidades").append("<optgroup label='Obra: " + response[i].nombre + "' >");

                if (response[i].sistema_onsite.length > 0) {

                    for (j = 0; j < response[i].sistema_onsite.length; j++) {
                        $("#sistema_onsite_id_unidades").append(
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

                        $("#sistema_onsite_id_unidades_interiores").append(
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

            idUnidadEdit = $('#sistema_onsite_id_unidades_edit').val();

            $('#sistema_onsite_id_unidades').val(idUnidadEdit);
            $('#sistema_onsite_id_unidades').trigger('change');

            if (idUnidadEdit < 1) {
                getIdSistema(); //consulta si hay variable de sesión enviada con id de sistema -creación-
            }

        };


    });
}

function getIdSistema() {

    var route = "/checkIdSistema/";

    $.get(route, function (response, state) {

        idSistema = response;

        $('#sistema_onsite_id').val(idSistema);
        $('#sistema_onsite_id').trigger('change');
       

    });
}

function storeEtiqueta(idUnidadInterior) {
    dataForm = setDataStoreEtiqueta(idUnidadInterior);

    $.ajax({
        url: '/storeEtiqueta/' + idUnidadInterior,
        type: 'POST',
        data: dataForm,
        contentType: false,
        processData: false,
        success: function (data) {
            showToast('Registro creado correctamente: ' + data.id, '', 'success');
            $('#nombre_etiqueta').val('');
            getEtiquetasPorIdUnidadInterior(data.unidad_interior_id);
            /* $("#createEtiquetaModal").modal('toggle'); */

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

function setDataStoreEtiqueta(idEtiqueta) {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    /* ************************ */

    var dataForm = new FormData();
    dataForm.append('_token', CSRF_TOKEN);

    dataForm.append('unidad_interior_id', idEtiqueta);
    dataForm.append('nombre', $('#nombre_etiqueta').val());



    return dataForm;
}

function getEtiquetas(idUnidadInterior) {

    return $.ajax({
        url: "/getEtiquetas/" + idUnidadInterior,
        type: 'GET',
    }
    );
}

function getEtiquetasPorIdUnidadInterior(idUnidadInterior) {
    getEtiquetas(idUnidadInterior).then(detalle => {
        
        $('.filas_etiquetas').html('');
        
        for (let i = 0; i < detalle.length; i++) {
            

            $('.filas_etiquetas').append(
                '<tr>'
                
                +'<td>'
                +detalle[i].id
                +'</td>'

                +'<td>'
                +detalle[i].nombre
                +'</td>'

                +'<td>'
                +'<a type="button" class="btn elimina_etiqueta" data-idEtiqueta="'
                +detalle[i].id
                +'">'
                +'<i class="pe-7s-trash"> </i></a>'
                +'</td>'                
                
                +'</tr>'
                );
            
        }
    
    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });
}

function delEtiqueta(idEtiqueta) {

    return $.ajax({
        url: "/delEtiqueta/" + idEtiqueta,
        type: 'GET',
    }
    );
}

function delEtiquetaPorId(idEtiqueta) {
    delEtiqueta(idEtiqueta).then(detalle => {
        
        showToast('Registro eliminado correctamente: ' + detalle.id, '', 'success');
        
        getEtiquetasPorIdUnidadInterior(detalle.unidad_interior_id);
        
    
    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });
}
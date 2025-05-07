function getObraOnsite(idObra) {
    var rutaModelos = "/getObraOnsite/" + idObra;


    $.get(rutaModelos, function (response, state) {


        if (response.length <= 0) {
            console.log('sin resultados');
        }
        else {
            $('#nombre').val(response.nombre);
        }

    });
}

function getTemplate(idTemplate) {

    return $.ajax({
        url: "/getTemplate/" + idTemplate,
        type: 'GET',
    }
    );
}

function unsetSessionVariable() {
    return $.ajax({
        url: '/unsetSessionVariable',
        type: 'GET',
        
    });
}

function getBouchersPorObra(idObra) {
    $bouchers = $.ajax({
        url: "/getBouchersPorObra/" + idObra,
        type: 'GET'
    });

    return $bouchers;
}

function getTarifaSolicitudPorObra(idSolicitud, idObra) {
    $tarifa = $.ajax({
        url: "/getTarifaSolicitudPorObra/" + idSolicitud +'/' +idObra,
        type: 'GET',
        async: false,
    });

    return $tarifa;
}

function getAllBouchersPorObra(idObra) {
    $bouchers = $.ajax({
        url: "/getAllBouchersPorObra/" + idObra,
        type: 'GET'
    });

    return $bouchers;
}

function getObraConSistema(idObra) {
    $obra = $.ajax({
        url: "/getObraConSistema/" + idObra,
        type: 'GET'
    });

    return $obra;
}

function getSolicitudesPorSistema(idsSistema) {
    $solicitudes = $.ajax({
        url: "/getSolicitudesPorSistema/" + idsSistema,
        type: 'GET'
    });

    return $solicitudes;
}






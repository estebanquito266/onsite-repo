function getObrasPorMes() {

    return $.ajax({
        url: "/getObras/",
        type: 'GET',
    }
    );
}

function getVisitasPorObra() {

    return $.ajax({
        url: "/getObrasConVisitas/",
        type: 'GET',
    }
    );
}

function getVisitasPorTecnico() {

    return $.ajax({
        url: "/getVisitasPorTecnico/",
        type: 'GET',
    }
    );
}


function getObrasSinObservaciones() {
    return $.ajax({
        url: '/getObrasSinObservaciones/',
        type: 'GET'
    });
}


function getResultadosReparacionPorEmpresaInstaladora() {
    return $.ajax({
        url: '/getResultadosReparacionPorEmpresaInstaladora/',
        type: 'GET'
    });
}

function getResultadosReparacionPorTecnico() {
    return $.ajax({
        url: '/getResultadosReparacionPorTecnico/',
        type: 'GET'
    });
}

function getPromedioCoordinadasCerradas() {
    return $.ajax({
        url: '/getPromedioCoordinadasCerradas/',
        type: 'GET'
    });
}






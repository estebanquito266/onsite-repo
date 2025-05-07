$(function () {

    setInterval(getNotification(), 1000);


    $('.botonExportador').on('click', function () {


        $("#modalExportador").modal('toggle');
        var loader = makeLoader('Procesando y exportando información');
        $('.bodyModalExportador').html(loader);

        setTimeout(() => {
            $("#modalExportador").modal('toggle');
            showToast('Archivo exportado correctamente', 'carrito', 'success');
        }, 6000);


    });

    $('#cerrarModalExportador').on('click', function () {
        $("#modalExportador").modal('toggle');

    });


});

function getNotification() {
    $.ajax({
        url: '/notifica_eventos',
        type: 'GET',
        dataType: 'JSON',
        success: function (data) {
            if (data)
                showToast(data, null, 'success');

            setTimeout(() => {
                getNotification();
            }, 10000);
        },

        fail: function () {
            showToast('No es posible notificar eventos', null, 'error');
        }
    }
    );
}


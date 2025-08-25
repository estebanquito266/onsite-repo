$(document).ready(function () {

    $("#reparacion_id").focusout(function () {
        exists($("#reparacion_id").val());
    });

    function exists(idReparacion) {

        var route = '/findReparacionById/' + idReparacion;

        if ($("#reparacionDataSucursal").length > 0) $("#reparacionDataSucursal").text("").hide();
        if ($("#reparacionDataEstado").length > 0) $("#reparacionDataEstado").text("").hide();
        if ($("#reparacionDataClave").length > 0) $("#reparacionDataClave").text("").hide();

        if ($("#reparacionDataSucursalModal").length > 0) $("#reparacionDataSucursalModal").text("").hide();
        if ($("#reparacionDataEstadoModal").length > 0) $("#reparacionDataEstadoModal").text("").hide();
        if ($("#reparacionDataClaveModal").length > 0) $("#reparacionDataClaveModal").text("").hide();

        $('#reparacionMsg').empty();
        if ($("#reparacion_id").val() == "")
            return;

        $.get(route, function (response, state) {
            console.log(response);
            if (response.length <= 0) {
                $("#reparacion_id").val("");
                $('#reparacionMsg').append("Ingrese una Reparación válida");
            }
            else if (response.length > 0) {
                $("#reparacion_id").val(response[0].id);
                $('#reparacionMsg').append("Reparación verificada correctamente");

                setDataBySearchClientReparation(response[0]);

                buscarClienteReparacion(response[0].id_empresa_onsite, 1);
            }

        });
    }
    function buscarClienteReparacion(idCliente, tipoTicket) { // buscar cliente segun el id ingresado
        $("#cliente_id").empty(); // al buscar, antes, limpio selects 

        if (tipoTicket == 1) {//reparacion
            var route = "/findClienteReparacionById/" + idCliente;
        } else {
            return;
        }
        var i = 0;

        $.get(route, function (response, state) {
            // if (response.length <= 0)
            // {
            //     $("#cliente_id").append("<option selected='selected' value=''>Cliente no encontrado</option>");
            // }
            // if (response.length > 1)
            // {
            //     $("#cliente_id").append("<option selected='selected' value=''>Seleccione el cliente - </option>");
            //     for (i = 0; i < response.length; i++) {
            //         $("#cliente_id").append("<option value='" + response[i].id + "'> " + response[i].nombreDniCuit + "</option>");
            //     }
            // }
            
            setDataBySearchClientReparation(response);
        });
    };

    function setDataBySearchClientReparation(data)
    {
        const existHtmlDataClave = $("#reparacionDataClave");
        const existHtmlDataSucursal = $("#reparacionDataSucursal");
        const existHtmlDataEstado = $("#reparacionDataEstado");
        const existHtmlDataClaveModal = $("#reparacionDataClaveModal");
        const existHtmlDataSucursalModal = $("#reparacionDataSucursalModal");
        const existHtmlDataEstadoModal = $("#reparacionDataEstadoModal");

        console.log(data, "aca!");
        if (data.clave && existHtmlDataClave.length > 0 && data.sucursal_onsite) {
            existHtmlDataClave.text("Clave: " + data.clave).show();
        }
        if (data.sucursal_onsite && existHtmlDataSucursal.length > 0) {
            existHtmlDataSucursal.text("Sucursal: " + data.sucursal_onsite.razon_social).show();
        }
        if (data.estado_onsite && existHtmlDataEstado.length > 0) {
            existHtmlDataEstado.text("Estado: " + data.estado_onsite.nombre).show();
        }

        if (data.clave && existHtmlDataClaveModal.length > 0) {
            existHtmlDataClaveModal.text("Clave: " + data.clave).show();
        }
        if (data.sucursal_onsite && existHtmlDataSucursalModal.length > 0) {
            existHtmlDataSucursalModal.text("Sucursal: " + data.sucursal_onsite.razon_social).show();
        }
        if (data.estado_onsite && existHtmlDataEstadoModal.length > 0) {
            existHtmlDataEstadoModal.text("Estado: " + data.estado_onsite.nombre).show();
        }

        if (data.id && !data.sucursal_onsite) {
            $("#cliente_id").append("<option value='" + data.id + "' selected></option>");
        }
    }

})
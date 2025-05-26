$(document).ready(function () {
    var clientes;

    $("#buscarCliente").click(function () {
        let texto = $("#textoBuscar").val();
        let ruta = '/buscarClienteConReparaciones'
        let data = {
            'textoBuscar': texto
        }

        $.ajax({
            url: ruta,
            type: 'GET',
            dataType: 'json',
            data: data,
            success: function (response) {
                $("#cliente_id").empty();
                clientes = response;
                if (response.length <= 0)
                    $("#cliente_id").append("<option selected='selected' value=''>Clientes no encontrados</option>");
                else
                    $("#cliente_id").append("<option selected='selected' value=''>Seleccione el cliente - </option>");

                for (i = 0; i < response.length; i++) {
                    $("#cliente_id").append("<option value='" + response[i].id + "'> [" + response[i].id + "] " + response[i].nombre + " - " + response[i].dni_cuit + "</option>");
                }
            }
        });

    });

    $("#cliente_id").change(function () {
        
        let cliente_id = $(this).val();
        console.log(clientes);
        let cliente = clientes.find(element => {
            if (element.id == cliente_id) {
                return element;
            }
        });

        if (cliente.reparaciones.length <= 0)
            $("#reparacion_id").append("<option selected='selected' value=''>Cliente sin Reparaciones</option>");
        else
            $("#reparacion_id").append("<option value=''>Seleccione la Reparación - </option>");

        for (i = 0; i < cliente.reparaciones.length; i++) {
            $("#reparacion_id").append("<option value='" + cliente.reparaciones[i].id + "'> " + cliente.reparaciones[i].id + "</option>");
        }
    });
});
$(function () {


    $([document.documentElement, document.body]).animate({
        scrollTop: $(".card_inicio_repuestos").offset().top
    }, 500);

    getModelosCategoria(0);

    var dataSetFooter = [];
    var dataSet = [];

    $("#detalle_pieza").hide();

    idOrden = $("#orden_id").val();

    if (idOrden > 0) {
        $("#orden_respuestos_id").val(idOrden);
        var ruta = "/editDetalleOrden/" + idOrden;
        const dataSet = [];

        $.get(ruta, function (response) {

            for (i = 0; i < response.length; i++) {
                index = i;
                completeDataFooter(dataSetFooter,
                    response[i].pieza_respuestos_id,
                    '',
                    '',
                    '',
                    '',
                    response[i].pieza.part_name,
                    response[i].pieza.numero,
                    response[i].pieza.spare_parts_code,
                    response[i].precio_fob,
                    index,
                    response[i].cantidad,
                    response[i].id,
                    response[i].pieza.moneda
                );
            }

        });

        console.log('dirige desde edit..');

    }
    else (
        console.log('no procede desde edit.. crea...')
    );

    $('#botonapp').on('click', function () {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var email = $('meta[name="email"]').attr('content');
        var password = $('meta[name="password"]').attr('content');

        $.ajax({
            url: 'https://onsite-test.speedup.com.ar/api.onsite/v1/login',
            type: 'POST',
            /* headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin': '*',
                
              },  */
            data:
            {
                _token: CSRF_TOKEN,
                email: email,
                password: 'test1234'

            },
            dataType: 'JSON',
            success: function (data) {

                console.log(data);

                $.ajax({
                    //url: 'https://onsite-test.speedup.com.ar/api.onsite/v1/reparaciones_onsite?filter[activas]=1&sort=id_estado',
                    url: 'https://app-test.speedup.com.ar/reparaciones/dashboard',
                    type: 'GET',
                    /* headers: {
                         'Accept': 'application/json',
                        'Content-Type': 'application/json', 
                        'Access-Control-Allow-Origin': 'http://onsite.local',
                         'Access-Control-Allow-Credentials': true, 
                        'Authorization': 'Bearer '+ data.token,
                      }, */
                    /* data: 
                    {
                            _token: CSRF_TOKEN,
                        email: email,
                        password: 'test1234'
        
                    }, */
                    dataType: 'JSON',
                    success: function (data) {

                        console.log(data);
                        setTimeout(function () {
                            showToast('Redirigiendo a APP correctamente', 'confirmacion', 'success');

                            //window.location.href = "https://app-test.speedup.com.ar/reparaciones/dashboard";
                        }, 5000);

                    }
                }
                );

                /* setTimeout(function () {
                 showToast('Redirigiendo a APP correctamente', 'confirmacion', 'success');
                 
                 window.location.href = "https://app-test.speedup.com.ar/reparaciones/dashboard";
             }, 5000); */

                /* if (data.status === 200) {
                    localStorage.setItem('token', data.token)
                    currentUser = data.user
    
                    console.log(currentUser);
                    return currentUser
                  } */


            }
        }
        );
    });

    $('#resetbutton').on('click', function (e) {
        e.preventDefault();
        $('#categoria_respuestos_id').val(0);
        $('#categoria_respuestos_id').trigger('change');
        $('#modelo_respuestos_id').html('');
        $('#spare_parts_code').val('');
        $('#part_name').val('');
        $("#despiece_modelo").html('');
    });


    $('.aprobar_index_btn').on('click', function () {
        idorden = $(this).data('idorden');
        updateEstadoOrdenRespuestos(idorden, 2, 'success');

        $(".grupo_actions").removeClass('show');
        $(".grupo_actions_inner").removeClass('show');

    });

    $('.rechazar_index_btn').on('click', function () {
        idorden = $(this).data('idorden');
        updateEstadoOrdenRespuestos(idorden, 3, 'warning');

        $(".grupo_actions").removeClass('show');
        $(".grupo_actions_inner").removeClass('show');
    });

    $('.devolver_index_btn').on('click', function () {
        idorden = $(this).data('idorden');
        updateEstadoOrdenRespuestos(idorden, 4, 'success');

        $(".grupo_actions").removeClass('show');
        $(".grupo_actions_inner").removeClass('show');
    });

    $('#respuestosOnsiteForm').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#categoria_respuestos_id").on('change', function () {

        $('.categoria_modelo').block({
            message: $(
                
                '<div class="loader mx-auto">\n' +
                
                '  <div class="ball-grid-pulse">\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '      <div class="bg-white"></div>\n' +
                '  </div>\n' +
                '                        </div>')
        });
    
    var idCategoria = $("#categoria_respuestos_id").val();
    getModelosCategoria(idCategoria);
});

$("#modelo_respuestos_id").on('change', function () {

    var idModelo = $("#modelo_respuestos_id").val();
    showDespieceModelo(idModelo);
    getPiezasModelos(idModelo);
});

$("#spare_parts_code_filter").on('click', function (element) {
    element.preventDefault();
    partCode = $('#spare_parts_code').val();
    
    $('.codigo-pieza-select').addClass('d-none');
    getPiezasCode(partCode);

})

$("#codigo-pieza-select").on('change', function () {
    var idPieza = $("#codigo-pieza-select").val();
    var idModelo = $("#codigo-pieza-select").find(':selected').data('modelo');
    
    getPiezaCode(idPieza, true);
    showDespieceModelo(idModelo);    
});

$("#part_name_filter").on('click', function (element) {
    element.preventDefault();
    partName = $('#part_name').val();
    idModelo = $('#modelo_respuestos_id').val();
    getPiezasName(partName, idModelo);

    $("#despiece_modelo").fadeOut("slow");

    $([document.documentElement, document.body]).animate({
        scrollTop: $(".listado_piezas").offset().top
    }, 500);
})

/* $("#description_filter").on('click', function (element) {
    element.preventDefault();
    partDescription = $('#description').val();
    getPiezasDescription(partDescription);

    $("#despiece_modelo").fadeOut("slow");

    $([document.documentElement, document.body]).animate({
        scrollTop: $(".listado_piezas").offset().top
    }, 500);
}) */

$('.table_respuestos').on('mouseover', '.add_button', function () {
    id = $(this).data('idrespuesto');
    cantidadInput = $('[data-idcantidad=' + id + ']').val();
    console.log('cantidad input: ' + cantidadInput);
    if (cantidadInput < 0) alertDisabledToast();
});

$('.table_respuestos').on('click', '.add_button', function () {

    idRespuesto = $(this).data('idrespuesto');
    part_name = $(this).data('part_name');
    numero = $(this).data('numero');
    moneda = $(this).data('moneda');
    vencimiento_precio = $(this).data('vencimiento');

    idCategoria = $("#categoria_respuestos_id").val();
    nameCategoria = $("#categoria_respuestos_id option:selected").text();

    idModelo = $("#modelo_respuestos_id").val();
    nameModelo = $("#modelo_respuestos_id option:selected").text();

    spare_parts_code = $(this).data('spare_parts_code');

    /* controla que no se encuentre vencido el precio según la fecha del listado de piezas */
    var today = new Date();
    var vencimiento = new Date(vencimiento_precio);

    if (today > vencimiento) {
        showToast('Precio se encuentra vencido. Consulte', 'carrito', 'error');
        precio_fob = 0;
    }

    else {
        console.log(vencimiento_precio);
        precio_fob = parseFloat($(this).data('precio_fob')).toFixed(2);
    }

    /* ******************************* */

    index = dataSetFooter.length;

    cantidad = $('.input_respuestos' + idRespuesto).val();

    precio_total = parseInt(cantidad) * parseFloat(precio_fob);
    descuento = parseFloat($('#descuento_user').val());
    subtotal_descuento = precio_total * (1 - (descuento / 100));
    descuento_monto = parseFloat(precio_total - subtotal_descuento).toFixed(2);
    precio_neto = parseFloat(precio_total) - parseFloat(descuento_monto);

    $("#pieza_respuestos_id").val(idRespuesto);

    /* controla que no se haya generado YA la orden con el primer respuesto insertado */
    idOrden = $("#orden_id").val();
    $("#orden_respuestos_id").val(idOrden);

    if (idOrden == 0) {
        var variablesFormularioOrden = $("#respuestosOnsiteForm").serialize();
        storeOrdenRespuestos(variablesFormularioOrden, dataSetFooter, moneda, idOrden, idRespuesto, cantidad, precio_fob, precio_total, precio_neto);
    }

    else {

        storeDetalleOrdenRespuestos(dataSetFooter, moneda, idOrden, idRespuesto, cantidad, precio_fob, precio_total, precio_neto);
    }

    showToast('Repuesto agregado correctamente. Click para ir al carrito', 'carrito', 'success');


});

$('.table_respuestos').on('keyup change', '.cantidad', function () {
    cantidad = $(this).val();
    console.log(cantidad);
    idSubtotal = $(this).data('idcantidad');
    precio_fob = $(this).data('precio');
    simbolo = $(this).data('simbolo') + ' ';

    calcula_subtotal(cantidad, precio_fob, idSubtotal, simbolo);

    id = $(this).data('idcantidad');
    id_val = $('[data-idrespuesto=' + id + ']').data('idrespuesto');


    if (parseInt(cantidad) > 0) {
        id = $(this).data('idcantidad');
        $('[data-idrespuesto=' + id + ']').prop("disabled", false);
    }
    else {
        id = $(this).data('idcantidad');
        $('[data-idrespuesto=' + id + ']').prop("disabled", true);
    };

});

$('.table_respuestos').on('click', '.del_button', function () {

    index = $(this).data('idrespuesto');
    moneda = $(this).data('moneda');
    idDetallePedido = $(this).data('iddetallepedido');
    deleteDataFooter(dataSetFooter, index, idDetallePedido, moneda);
});

$('.table_respuestos').on('click', '.showPiezaButton', function () {
    idPieza = $(this).data('idpieza');

    getPieza(idPieza);

    $("#detalle_pieza").fadeIn("slow");

    $([document.documentElement, document.body]).animate({
        scrollTop: $("#detalle_pieza").offset().top
    }, 500);

});

$('#detalle_pieza').on('click', '.modelo_ver', function () {

    idModelo = $(this).data('idmodelo');

    showDespieceModelo(idModelo);
    getPiezasModelos(idModelo);
    console.log('mostrando piezas por link del modelo');

});

$('.table_respuestos').on('change', '.cantidad_footer', function () {

    idDetalle = $(this).data('iddetallefooter');
    cantidad = $(this).val();
    simbolo = $(this).data('simbolo');
    precio = $(this).data('preciofooter');

    idCantidad = $(this).data('idcantidadfooter');
    part_name = $(this).data('part_name');
    numero = $(this).data('numero');
    spare_parts_code = $(this).data('spare_parts_code');
    moneda = $(this).data('moneda');
    subtotal = parseInt(cantidad) * parseFloat(precio);


    descuento = parseFloat($('#descuento_user').val());
    subtotal_descuento = subtotal * (1 - (descuento / 100));
    descuento_monto = parseFloat(subtotal - subtotal_descuento).toFixed(2);

    $('.span_respuestos_footer' + idDetalle).html(simbolo + ' ' + parseFloat(subtotal).toFixed(2));
    $('.span_respuestos_footer_descuento' + idDetalle).html(simbolo + ' ' + parseFloat(subtotal_descuento).toFixed(2));

    /* modifico las cantidades y valores de la tabla de arriba */
    $('.input_respuestos' + idCantidad).val(cantidad);
    calcula_subtotal(cantidad, precio, idCantidad, simbolo);


    /* modifica el array de la tabla de arriba para reinsertar el footer (carrito) en caso que se 
    inserte otro elemento más */
    /* input_cantidad_nuevo = makeInputFooter('cantidad_footer form-control input_respuestos_footer' + idCantidad,
        'cantidad_footer', idCantidad, precio, cantidad, idDetalle, simbolo);

    console.log('datafooter a actualizar');
    console.log(dataSetFooter);

    index_update = dataSetFooter.map(function (element) {
        element[9] = input_cantidad_nuevo;
    }).indexOf(idCantidad); */ //element[9] es el input que contiene las cantidades       

    /* ************************************* */

    /* alternativa complete data footer */
    completeDataFooter(dataSetFooter, idCantidad, idCategoria, nameCategoria, idModelo, nameModelo,
        part_name, numero, spare_parts_code, precio, index, cantidad, idDetalle, moneda);
    /* @@@@@@@@@@@@@@@@@@@ */

    updateDetalleOrdenRespuestos(idDetalle, cantidad, subtotal, subtotal_descuento);

    recalculateTotal(simbolo);

});



$('#hide_detalle').on('click', function () {
    $("#detalle_pieza").fadeOut("slow");
    $([document.documentElement, document.body]).animate({
        scrollTop: $(".listado_piezas").offset().top
    }, 500);
});


$("#botonGuardar").on('click', function (event) {
    event.preventDefault();
    idOrden = $("#orden_id").val();

    /* llamo a la función y proceso la promesa con then */
    getDetalleOrden(idOrden).then(detalle => {
        console.log('recorriendo monto....');
        precio = 0;
        for (let key in detalle) {

            if (detalle[key].pieza.precio) {
                console.log(key, detalle[key].pieza.precio[0].precio_fob);
                precio += detalle[key].pieza.precio[0].precio_fob * detalle[key].cantidad;
            }

        };
        console.log('Suma de precio: ' + precio);

        if (precio > 2000) {
            if (detalle.length > 0) {
                $("#modalUserRepuestos").modal('toggle');
                $("#confirmarModal").attr('data-estadopedido', 'REVISION');
            }
            else showToast('No ha agregado ningún repuesto al carrito. Revise.', 'carrito', 'error');
        }
        else {
            showToast('Valor del pedido: USD' + precio + ', no alcanza el mínimo requerido de USD2000.', '', 'error');
            setTimeout(() => {

                $("#modalUserRepuestos").modal('toggle');
                $("#confirmarModal").html('Solicitar cotización');
                $("#confirmarModal").removeClass('btn-primary');
                $("#confirmarModal").addClass('btn-warning');
                $("#confirmarModal").attr('data-estadopedido', 'COTIZACION');

            }, 500);
        }

    })
        .catch(error => {
            console.log('Error al procesar la petición. TRACE: ');
            console.log(error.responseJSON.trace);
            showToast('ERROR, vea la consola para rastrear la causa', 'carrito', 'error');
        });
}
);

$('#cerrarModal').on('click', function () {
    $("#modalUserRepuestos").modal('toggle');
}
);

$('#confirmarModal').on('click', function () {


    idOrden = $("#orden_id").val();
    estado_pedido = $(this).data('estadopedido');

    if (idOrden > 0) {
        var variablesFormularioOrden = $("#respuestosOnsiteForm").serialize();

        nombre_solicitante = $('#nombre_solicitante').val();
        email_solicitante = $('#email_solicitante').val();
        empresa_onsite_id = $('#empresa_onsite_id').val();

        confirmarOrden(idOrden, nombre_solicitante, email_solicitante, empresa_onsite_id, dataSet, dataSetFooter, estado_pedido);
    }


});

$('#empresaModal').on('change', function () {
    var empresa = $(this).val();

    getUsuarioEmpresa(empresa);
});

$('.table_respuestos').on('click', '.editPiezaButton', function (element) {
    element.preventDefault();
    idPieza = $(this).data('idpieza');
    $("#modalEditPieza").modal('toggle');
    $('#confirmarModalEditPieza').attr('data-idpieza', idPieza);

    getPiezaEdit(idPieza);


}
);

$('#cerrarModalEditPieza').on('click', function () {
    $("#modalEditPieza").modal('toggle');
});

$('#confirmarModalEditPieza').on('click', function () {

    idPieza = $(this).data('idpieza');
    console.log(idPieza);
    updatePieza(idPieza);

}

);

$('.aceptar_condiciones').on('click', function () {

    if ($('#aceptar_condiciones').is(":checked")) {

        $('#confirmarModal').prop('disabled', false);
    }
    else
        $('#confirmarModal').prop('disabled', true);

});


});

function populateDataTable(dataSet) {

    if ($.fn.dataTable.isDataTable('#dtBasicExample')) {
        $('#dtBasicExample').DataTable().destroy();
    }

    var table = $('#dtBasicExample').DataTable({
        data: dataSet,
        columns: [
            { title: "id" },
            { title: "Ref." },
            { title: "Descripción" },
            { title: "Código de Pieza" },

            { title: "Precio de venta Sugerido" },
            { title: "Cantidad" },
            { title: "Sub- total" },
            { title: "Des- cuento" },
            { title: "Total" },
            { title: "Add" },
            { title: "" },
            { title: "numero_orden" },
        ],
        dom: 'Bfrtip',
        "order": [[11, "asc"]]

    });

    table.column(0).visible(false);
    table.column(11).visible(false);


    $('.add_button').prop("disabled", true);


}

function populateDataTableFooter(dataSetFooter) {

    /* $.fn.dataTable.ext.errMode = 'throw'; */

    if ($.fn.dataTable.isDataTable('#dtFooterRespuestos')) {

        $('#dtFooterRespuestos').DataTable().destroy();

    }
    var table = $('#dtFooterRespuestos').DataTable({
        data: dataSetFooter,
        columns: [
            { title: "id" },
            { title: "idCategoria" },
            { title: "Categoria" },
            { title: "idModelo" },
            { title: "Modelo" },

            { title: "Descripción" },
            { title: "Ref." },
            { title: "Código de Pieza" },

            { title: "Precio de venta sugerido" },
            { title: "Cantidad" },
            { title: "Sub- total" },
            { title: "Des- cuento" },
            { title: "Total" },
            { title: "Eliminar" },
        ],
        dom: 'Bfrtip',


    });


    //table.column(0).visible(false);
    table.column(1).visible(false);
    table.column(2).visible(false);
    table.column(3).visible(false);
    table.column(4).visible(false);
    //table.column(10).visible(false);


}

//---------------------------------------------------------------------------//
function getModelosCategoria(idCategoria) {
    var rutaModelos = "/selectModelosRespuestos/" + idCategoria;
    $("#modelo_respuestos_id").empty();

    $.get(rutaModelos, function (response, state) {




        if (response.length <= 0)
            $("#modelo_respuestos_id").append("<option selected='selected' value=''>Modelos no encontradas</option>");
        else {
            $("#modelo_respuestos_id").append("<option selected='selected' value=''>Seleccione el modelo - </option>");


            if (response.length == 1) {
                $("#modelo_respuestos_id").append("<option value='" + response[0].id + "'" + " data-despiece='" + response[0].imagen_despiece + "' selected> [" + response[0].id + "] " + response[0].nombre + "</option>");

                /* var idDespiece = response[0].imagen_despiece;
                showDespieceModelo(idDespiece);                
                */

                var idModelo = response[0].id;
                showDespieceModelo(idModelo);

            }

            else {

                for (i = 0; i < response.length; i++) {

                    if (response[i].modelo_pieza.length > 0) {
                        $("#modelo_respuestos_id").append("<option value='" + response[i].id + "'" + " data-despiece='" + response[i].imagen_despiece + "'> [" + response[i].id + "] " + response[i].nombre + "</option>");
                    }
                    else {
                        console.log('modelo vacio: ' + response[i].id)
                    }

                }

            }


        }

        $.unblockUI();
    });
}

function showDespieceModelo(idModelo) {

    $("#despiece_modelo").html(
        ''
    );

    var rutaModelos = "/getImagenPorModelo/" + idModelo;


    $.get(rutaModelos, function (response) {

        if (response.length == 0) {

            console.log('sin imagenes...');

            $("#despiece_modelo").html(
                "<img  class='img-fluid' src=/imagenes/" + 'default_repuestos.jpg' + ">"
            );
        }

        else {

            for (i = 0; i < response.length; i++) {
                console.log('imagen......' + response[i].imagen_despiece);

                if (i == 0) {
                    $("#despiece_modelo").append(
                        '<div class="carousel-item active">'
                        + '<a href="/imagenes/'
                        + response[i].imagen_despiece
                        + '" target="_blank">'
                        + '<img class="d-block w-100 img-fluid" src="/imagenes/'
                        + response[i].imagen_despiece
                        + '">'
                        + '</a>'
                        + '</div>'
                    );
                }

                else {


                    $("#despiece_modelo").append(
                        '<div class="carousel-item">'
                        + '<a href="/imagenes/'
                        + response[i].imagen_despiece
                        + '" target="_blank">'
                        + '<img class="d-block w-100 img-fluid" src="/imagenes/'
                        + response[i].imagen_despiece
                        + '">'
                        + '</a>'
                        + '</div>'
                    );

                    $('.carousel-control-prev-icon').addClass('color_boton_imagen_izq');
                    $('.carousel-control-next-icon').addClass('color_boton_imagen_der');


                }

            }

        }




    });



    /* modelo viejo */

    /* if (idModelo == null) {
        $("#despiece_modelo").html(
            "<img  class='img-fluid' src=/imagenes/" + 'default_repuestos.jpg' + ">"
        );


        $('#part_image_div').html(
            '<img src="'
            + '/imagenes/' + 'default_repuestos.jpg'
            + '"></img>'
        );
    }

    else {
        $("#despiece_modelo").html(
            "<img  class='img-fluid' src=/imagenes/" + idModelo + ">"
        );
    }
    
     */

    $("#despiece_modelo").fadeIn("slow");

    $([document.documentElement, document.body]).animate({

        scrollTop: $(".imagen_despiece").offset().top

    }, 500);
}

/* function showDespieceModelo(idDespiece) {

    if (idDespiece == null) {
        $("#despiece_modelo").html(
            "<img  class='img-fluid' src=/imagenes/" + 'default_repuestos.jpg' + ">"
        );


        $('#part_image_div').html(
            '<img src="'
            + '/imagenes/' + 'default_repuestos.jpg'
            + '"></img>'
        );
    }

    else {
        $("#despiece_modelo").html(
            "<img  class='img-fluid' src=/imagenes/" + idDespiece + ">"
        );
    }
    
    

    $("#despiece_modelo").fadeIn("slow");

    $([document.documentElement, document.body]).animate({

        scrollTop: $(".imagen_despiece").offset().top

    }, 500);
} */

function getPiezasModelos(idModelo) {

    var rutaModelos = "/selectPiezasRespuestos/" + idModelo;
    descuento = parseFloat($('#descuento_user').val());
    let dataSet = [];

    $.get(rutaModelos, function (response) {

        
        //console.log(response[0].pieza_respuestos_onsite.precio[0].vencimiento_precio);
        for (i = 0; i < response.length; i++) {
            index = i;
            idDetallePedido = 0; //aún no se crea el detalle

            //evalúo si hay precio en la tabla de actualización y en caso contrario tomo el valor original de la tabla de precios
            if (response[i].pieza_respuestos_onsite.precio.length > 0) {
                precio_fob = response[i].pieza_respuestos_onsite.precio[0].precio_fob;
                vencimiento_precio = response[i].pieza_respuestos_onsite.precio[0].vencimiento_precio;
            }

            else {
                precio_fob = response[i].pieza_respuestos_onsite.precio_fob;
                vencimiento_precio = '2022-06-30';
            }


            /* ------------- */

            if (vencimiento_precio == '2022-06-30') //TODO comparar con hoy !!

            {
                dataSet = makeDataSetPiezas(response[i].pieza_respuestos_onsite.id, response[i].pieza_respuestos_onsite.part_name,
                    response[i].numero, response[i].pieza_respuestos_onsite.spare_parts_code,
                    response[i].pieza_respuestos_onsite.moneda,
                    0, index, idDetallePedido, vencimiento_precio, response[i].numero_orden, dataSet);
            }

            else {
                dataSet = makeDataSetPiezas(response[i].pieza_respuestos_onsite.id, response[i].pieza_respuestos_onsite.part_name,
                    response[i].numero, response[i].pieza_respuestos_onsite.spare_parts_code,
                    response[i].pieza_respuestos_onsite.moneda,
                    precio_fob, index, idDetallePedido, vencimiento_precio, response[i].numero_orden, dataSet);
            }

        }

        populateDataTable(dataSet);

        /* var dataSet = [
            ["Tiger Nixon", "System Architect", "Edinburgh", "5421", "2011-04-25", "$320,800"],
            ["Garrett Winters", "Accountant", "Tokyo", "8422", "2011-07-25", "$170,750"],
            ["Garrett Winters", "Accountant", "Tokyo", "8422", "2011-07-25", "$170,750"],
        ]; */
    });

}

function getPiezasCode(partCode) {
    var rutaModelos = "/getPiezasCode/" + partCode;

    $.ajax({
        type: "GET", 
        url: rutaModelos, 
        success: function (response) {
            response = Object.keys(response).map(function (key) { return response[key]; });
            var resultados = response.length;

            if(resultados == 1) {
                procesarPieza(response[0]);

                $("#despiece_modelo").fadeOut("slow");
                $([document.documentElement, document.body]).animate({
        
                    scrollTop: $(".listado_piezas").offset().top
        
                }, 500);                

            }
            else if(resultados > 1) {
                $('.codigo-pieza-select').removeClass('d-none');
                $('.codigo-pieza-select select').find('option').remove();

                $('.codigo-pieza-select select').append('<option value="">Seleccione una pieza</option>');
                $.each(response, function(k, pieza) {
                    $('.codigo-pieza-select select').append('<option value="'+pieza.id+'" data-modelo="'+pieza.modelo_pieza[0].modelo_respuestos_onsite.id+'">'+pieza.spare_parts_code+' | '+pieza.numero+' | '+pieza.modelo_pieza[0].modelo_respuestos_onsite.nombre+'</option>');
                });                
            }
        }
    });

}

function getPiezaCode(partCode, withModelo = false) {
    var rutaModelos = "/getPiezaCode/" + partCode;

    $.get(rutaModelos, function (response) {

        procesarPieza(response);

        if(!withModelo) {
            $("#despiece_modelo").fadeOut("slow");
        }
        $([document.documentElement, document.body]).animate({

            scrollTop: $(".listado_piezas").offset().top

        }, 500);            

    });

}

function procesarPieza(pieza) {
            /* evalúo si hay precio en la tabla de actualización y en caso contrario tomo el valor original de la tabla de precios 
        evalúo si hay vencimiento */
        let dataSet = [];

        if (pieza && pieza.precio && pieza.precio.length > 0) {
            precio_fob = pieza.precio[0].precio_fob;
            vencimiento_precio = pieza.precio[0].vencimiento_precio;
        }

        else {
            precio_fob = pieza.precio_fob;
            vencimiento_precio = '2022-06-30';
        }


        /* ***************************** */

        idDetallePedido = 0; //aún no se crea el detalle
        index = 1;
        dataSet = makeDataSetPiezas(
            pieza.id,
            pieza.part_name,
            pieza.numero,
            pieza.spare_parts_code,
            pieza.moneda,
            precio_fob,
            index,
            idDetallePedido,
            vencimiento_precio,
            pieza.modelo_pieza[0].numero_orden,
            dataSet);


        populateDataTable(dataSet);
}

function getPiezasName(partName, idModelo) {
    var rutaModelos = "/getPiezasName/" + partName;
    let dataSet = [];

    $.get(rutaModelos, function (response) {

        for (i = 0; i < response.length; i++) {

            /* evalúo si hay precio en la tabla de actualización y en caso contrario tomo el valor original de la tabla de precios  */
            if (response[i].precio.length > 0) {
                precio_fob = response[i].precio[0].precio_fob;
                vencimiento_precio = response[i].precio[0].vencimiento_precio;
            }
            else {
                precio_fob = response[i].precio_fob;
                vencimiento_precio = '2022-06-30';
            }

            /* ***************************** */

            if (response[i].modelo_pieza[0].modelo_respuestos_id == idModelo) {
                index = i;
                idDetallePedido = 0; //aún no se crea el detalle
                dataSet = makeDataSetPiezas(
                    response[i].id,
                    response[i].part_name,
                    response[i].modelo_pieza[0].numero,
                    response[i].spare_parts_code,
                    response[i].moneda,
                    precio_fob,
                    index,
                    idDetallePedido,
                    vencimiento_precio,
                    response[i].modelo_pieza[0].numero_orden,
                    dataSet);
            }

        }

        populateDataTable(dataSet);

    });

}

function getPiezasDescription(partDescription) {
    var rutaModelos = "/getPiezasDescription/" + partDescription;
    let dataSet = [];

    $.get(rutaModelos, function (response) {

        /* evalúo si hay precio en la tabla de actualización y en caso contrario tomo el valor original de la tabla de precios  */
        if (response[i].precio.length > 0) {
            precio_fob = response[i].precio[0].precio_fob;
            vencimiento_precio = response[i].precio[0].vencimiento_precio;
        }
        else {
            precio_fob = response[i].precio_fob;
            vencimiento_precio = '2022-06-30';
        }

        /* ***************************** */
        for (i = 0; i < response.length; i++) {
            index = i;
            idDetallePedido = 0; //aún no se crea el detalle
            dataSet = makeDataSetPiezas(
                response[i].id,
                response[i].part_name,
                response[i].modelo_pieza[0].numero,
                response[i].spare_parts_code,
                response[i].moneda,
                precio_fob,
                index,
                idDetallePedido,
                vencimiento_precio,
                response[i].modelo_pieza[0].numero_orden,
                dataSet);
        }

        populateDataTable(dataSet);
    });

}

function getDetalleOrden(idOrden) {

    return $.ajax({
        url: "/getDetalleOrden/" + idOrden,
        type: 'GET',
    }
    );
}


function makeDataSetPiezas(idPieza, part_name, numero, spare_parts_code, moneda, precio_fob, index,
    idDetallePedido, vencimiento_precio, numero_orden, dataSet) {


    let simbolo = '';
    if (moneda == 'dolar') simbolo = 'USD ';
    if (moneda == 'euro') simbolo = 'EUR ';
    if (moneda == 'peso') simbolo = 'AR$ ';

    button = makeButton('button', 'add_button', idPieza, part_name, numero,
        spare_parts_code, precio_fob, index, idDetallePedido, 'btn btn-primary btn-sm add_button', 'Agregar', moneda, vencimiento_precio);

    input = makeInput('cantidad form-control input_respuestos' + idPieza, 'cantidad_usuario',
        idPieza, precio_fob, simbolo);

    span = makeSpan('subtotal', idPieza);

    span_descuento = makeSpan('subtotal_descuento', idPieza);

    span_sub = makeSpan('sub', idPieza);

    actionMenu = makeActions(idPieza);

    dataSet.push([idPieza, numero, part_name, spare_parts_code, simbolo + parseFloat(precio_fob).toFixed(2),
        input, span_sub, span, span_descuento, button, actionMenu, numero_orden]);

    return dataSet;

}

function calcula_subtotal(cantidad, precio_fob, idSubtotal, simbolo) {
    var subtotal = parseInt(cantidad) * parseFloat(precio_fob);

    var descuento = parseFloat($('#descuento_user').val());

    subtotal_descuento = (subtotal * (1 - (descuento / 100))).toFixed(2);

    $('.sub' + idSubtotal).html(simbolo + parseFloat(subtotal).toFixed(2));
    $('.subtotal' + idSubtotal).html(simbolo + parseFloat(subtotal - subtotal_descuento).toFixed(2));
    $('.subtotal_descuento' + idSubtotal).html(simbolo + parseFloat(subtotal_descuento).toFixed(2));
}

function makeButton(type, id, dataId, dataPart_name, dataNumero, dataSpare_parts_code, dataPrecio_fob, dataIndex,
    dataIdDetallePedido, buttonclass, text, moneda, vencimiento_precio) {
    button = "<button type=" + type + " id=" + id + " class='" + buttonclass
        + "' data-idrespuesto=" + dataId
        + " data-part_name=" + '"' + dataPart_name + '"'
        + " data-numero=" + dataNumero
        + " data-spare_parts_code=" + dataSpare_parts_code
        + " data-precio_fob=" + dataPrecio_fob
        + " data-index=" + dataIndex
        + " data-iddetallepedido=" + dataIdDetallePedido
        + " data-moneda=" + moneda
        + " data-vencimiento=" + vencimiento_precio

        + ">" + text + "</button>";



    return button;

}

function makeInput(inputClass, name, data_cantidad, data_precio, simbolo) {
    input = "<input type='number' min='0' class='" + inputClass + "' name=" + name
        + " data-idcantidad=" + data_cantidad
        + " data-precio=" + data_precio
        + " data-simbolo=" + simbolo
        + "></input>";

    return input;
}

function makeInputFooter(inputClass, name, data_cantidad, data_precio, cantidad, idDetallePedido, simbolo, part_name, numero, spare_parts_code, moneda) {
    input = "<input type='number' min='0' class='" + inputClass
        + "' name=" + name
        + " data-idcantidadfooter=" + data_cantidad
        + " data-preciofooter=" + data_precio
        + " data-iddetallefooter=" + idDetallePedido
        + " data-simbolo=" + simbolo
        + " data-part_name=" + part_name
        + " data-numero=" + numero
        + " data-spare_parts_code=" + spare_parts_code
        + " data-moneda=" + moneda
        + " value=" + cantidad
        + "></input>";

    return input;
}

function makeSpan(spanClass, spanId) {
    let span = "<span class='" + spanClass + spanId + "'></span>";

    return span;
}

function makeSpanSubtotalFooter(spanClass, spanId, subtotal, simbolo) {
    span = "<span class='" + spanClass + spanId + "'>"
        + simbolo + ''
        + subtotal
        + "</span>";

    return span;
}

function completeDataFooter(dataSetFooter, idRespuesto, idCategoria, nameCategoria, idModelo,
    nameModelo, part_name, numero, spare_parts_code, precio_fob, index, cantidad, idDetallePedido, moneda) {

    let simbolo = '';
    if (moneda == 'dolar') simbolo = 'USD ';
    if (moneda == 'euro') simbolo = 'EUR ';
    if (moneda == 'peso') simbolo = 'AR$ ';


    descuento = parseFloat($('#descuento_user').val());

    button = makeButton('button', 'del_button', idRespuesto, part_name, numero,
        spare_parts_code, precio_fob, index, idDetallePedido, 'btn btn-danger btn-sm del_button', 'Eliminar', moneda, '2022-06-30');

    subtotal = parseFloat(parseInt(cantidad) * parseFloat(precio_fob)).toFixed(2);

    subtotal_descuento = (subtotal * (1 - (descuento / 100))).toFixed(2);

    descuento_monto = parseFloat(subtotal - subtotal_descuento).toFixed(2);

    input_cantidad = makeInputFooter('cantidad_footer form-control input_respuestos_footer' + idRespuesto, 'cantidad_footer',
        idRespuesto, precio_fob, cantidad, idDetallePedido, simbolo, part_name, numero, spare_parts_code, moneda);

    //span_subtotal = makeSpanSubtotalFooter('span_respuestos_footer', idDetallePedido, subtotal);
    span_subtotal = makeSpanSubtotalFooter('span_respuestos_footer', idDetallePedido, descuento_monto, simbolo);

    span_subtotal_descuento = makeSpanSubtotalFooter('span_respuestos_footer_descuento', idDetallePedido, subtotal_descuento, simbolo);


    /* Evalúo si el idrespuesto ya fue insertado previamente y 
    si es true, lo borro y lo cargo nuevamente con las nuevas cantidades */

    indexRespuestoInsertado = dataSetFooter.map(function (element) { return element[0]; })
        .indexOf(idRespuesto); //element[0] es el idrespuesto

    if (indexRespuestoInsertado >= 0) {
        dataSetFooter.splice(indexRespuestoInsertado, 1);
    }



    dataSetFooter.push([idRespuesto, idCategoria, nameCategoria, idModelo, nameModelo, part_name,
        numero, spare_parts_code, simbolo + precio_fob, input_cantidad, simbolo + subtotal, span_subtotal,
        span_subtotal_descuento, button]);

    calculateTotal(dataSetFooter, moneda);

    populateDataTableFooter(dataSetFooter);

    setFieldsDetalle(idRespuesto, cantidad, precio_fob, subtotal, subtotal_descuento);


}


/* Se envía el parámetro idrespuesto de la línea que se quiere eliminar con indeOf se obtiene 
el INDEX del elemento del array. Se elimina ese index y se pasa el nuevo array a DataTable */
function deleteDataFooter(dataSetFooter, idrespuesto, idDetallePedido, moneda) {

    index_delete = dataSetFooter.map(function (element) { return element[0]; })
        .indexOf(idrespuesto); //element[0] es el idrespuesto

    dataSetFooter.splice(index_delete, 1);

    calculateTotal(dataSetFooter, moneda);

    deleteDetallePedido(idDetallePedido);

    populateDataTableFooter(dataSetFooter);

}

function storeOrdenRespuestos(variablesFormularioOrden, dataSetFooter, moneda, idOrden, idRespuesto, cantidad, precio_fob, precio_total, precio_neto) {

    return $.ajax({
        url: '/storeOrdenPedidoRespuestos',
        type: 'POST',
        data: variablesFormularioOrden,
        dataType: 'JSON',
        success: function (data) {
            $("#orden_id").val(data.id);
            $("#orden_respuestos_id").val(data.id);

            idOrdenReg = data.id;

            var variablesFormularioDetalleOrden = $("#respuestosOnsiteForm").serialize();
            storeDetalleOrdenRespuestos(/* variablesFormularioDetalleOrden, */ dataSetFooter, moneda, idOrdenReg, idRespuesto, cantidad, precio_fob, precio_total, precio_neto);

        }
    }
    );

}

function storeDetalleOrdenRespuestos(/* variablesFormularioDetalleOrden,  */dataSetFooter, moneda, idOrden,
    idRespuesto, cantidad, precio_fob, precio_total, precio_neto) {

    monto_dolar = $('#monto_dolar').val();
    monto_euro = $('#monto_euro').val();
    monto_peso = $('#monto_peso').val();

    company_id = $('#company_id').val();
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/storeDetalleOrdenRespuestos',
        type: 'POST',
        /* data: variablesFormularioDetalleOrden, */
        data: {
            _token: CSRF_TOKEN,
            orden_respuestos_id: idOrden,
            pieza_respuestos_id: idRespuesto,
            cantidad: cantidad,
            precio_fob: precio_fob,
            precio_total: precio_total,
            precio_neto: precio_neto,
            company_id: company_id,
            monto_dolar: monto_dolar,
            monto_euro: monto_euro,
            monto_peso: monto_peso

        },
        dataType: 'JSON',
        success: function (data) {

            idDetallePedido = data.id;

            completeDataFooter(dataSetFooter, idRespuesto, idCategoria, nameCategoria, idModelo, nameModelo,
                part_name, numero, spare_parts_code, precio_fob, index, cantidad, idDetallePedido, moneda);


        }
    }
    );
}

function setFieldsDetalle(idRespuesto, cantidad, precio_fob, subtotal, subtotal_descuento) {

    $("#pieza_respuestos_id").val(idRespuesto);
    $("#cantidad").val(cantidad);
    $("#precio_fob").val(precio_fob);
    $("#precio_total").val(subtotal);
    $("#precio_neto").val(subtotal_descuento);

}

function deleteDetallePedido(idDetallePedido) {

    var variablesFormularioDetalleOrden = $("#respuestosOnsiteForm").serialize();

    $.ajax({
        data: variablesFormularioDetalleOrden,
        url: '/deleteDetallePedido/' + idDetallePedido,
        type: 'POST',
        dataType: 'JSON',
        success: function (data) {
            console.log(data);
            return data;
        }
    }
    );
}

function confirmarOrden(idOrden, nombre_solicitante, email_solicitante, empresa_onsite_id, dataSet, dataSetFooter, estado_pedido) {

    var loader = makeLoader('Procesando y enviando notificaciones');
    $('.bodyModal').html(loader);

    $('.footer_modal_repuestos').hide();

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/confirmarOrden/' + idOrden,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            nombre_solicitante: nombre_solicitante,
            email_solicitante: email_solicitante,
            empresa_onsite_id: empresa_onsite_id,
            estado_pedido: estado_pedido
        },
        dataType: 'JSON',
        success: function (data) {

            loader = makeLoader('Orden Creada correctamente con el Nº: ' + data.id + '. Solicitante: ' + data.nombre_solicitante + '. Enviando Notificaciones.');
            $('.bodyModal').html(loader);


            setTimeout(function () {
                enviarEmailsRepuestos(data.id, data.nombre_solicitante, data.email_solicitante, data.empresa_onsite_id);
            }, 1000);

            setTimeout(function () {
                $("#modalUserRepuestos").modal('toggle');
                window.location.href = "/respuestosOnsite/create";
            }, 2000);


        }
    }
    );
}

function enviarEmailsRepuestos(idOrden, nombre_solicitante, email_solicitante, empresa_onsite_id) {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/enviarEmailsRepuestos/' + idOrden,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            nombre_solicitante: nombre_solicitante,
            email_solicitante: email_solicitante,
            empresa_onsite_id: empresa_onsite_id,
        },
        dataType: 'JSON',
        success: function (data) {

        },

        fail: function () {
            console.log('No es posible procesar la solicitud.');
        }
    }
    );

    return true;
}

$('.reenviar-email').on('click', function () {
    console.log('Reenviar email');
    let id = $(this).data('order_id');
    let nombre_solicitante = $(this).data('nombre_solicitante');
    let email_solicitante = $(this).data('email_solicitante');
    let empresa_onsite_id = $(this).data('empresa_onsite_id');
    enviarEmailsRepuestos(id, nombre_solicitante, email_solicitante, empresa_onsite_id);
});

function makeLoader(mensaje) {

    let loader = '<div class="font-icon-wrapper float-left mr-3 mb-3 col-12">'
        + '<div class="loader-wrapper d-flex justify-content-center align-items-center">'
        + '<div class="loader">'
        + '<div class="ball-spin-fade-loader">'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '</div>'
        + '</div>'
        + '</div>'
        + '<p>'
        + mensaje
        + '</p>'
        + '</div>';

    return loader;
}

function resetForm(dataSet, dataSetFooter) {

    resetTables(dataSet, dataSetFooter);

    $("#spare_parts_code").val('');

    $("#part_name").val('');

    $("#description").val('');

    $("#orden_id").val(0);
    $("#orden_respuestos_id").val(0);

    $("#despiece_modelo").fadeOut("slow");

    $([document.documentElement, document.body]).animate({

        scrollTop: $(".card_inicio_repuestos").offset().top

    }, 500);



}

function resetTables(dataset, dataSetFooter) {

    dataSetFooter.length = 0;
    dataset.length = 0;

    dataset = [['', '', '', '', '', '', '', '', '', '', '', '', '']];
    dataSetFooter = [['', '', '', '', '', '', '', '', '', '', '', '', '', '']];

    populateDataTable(dataset);

    populateDataTableFooter(dataSetFooter);


}

function calculateTotal(dataSetFooter, moneda) {


    let monto_total_dolar = 0;
    let monto_total_euro = 0;
    let monto_total_peso = 0;

    dataSetFooter.forEach(element => {
        let monto = element[10].substring(3);//quito el simbolo de moneda
        let simbolo = element[10][0] + element[10][1] + element[10][2];
        console.log('montos totales usd eur ar$');

        if (simbolo == 'USD') {
            monto_total_dolar = parseFloat(monto_total_dolar) + parseFloat(monto);
            console.log(monto_total_dolar);
            return monto_total_dolar;
        }

        if (simbolo == 'EUR') {
            monto_total_euro = parseFloat(monto_total_euro) + parseFloat(monto);
            console.log(monto_total_euro);
            return monto_total_euro;
        }

        if (simbolo == 'AR$') {
            monto_total_peso = parseFloat(monto_total_peso) + parseFloat(monto);
            console.log(monto_total_peso);
            return monto_total_peso;
        }


    });

    descuento = $('#descuento_user').val();

    monto_descuento_dolar = (parseFloat(descuento) / 100) * parseFloat(monto_total_dolar);
    monto_final_dolar = parseFloat(monto_total_dolar) - monto_descuento_dolar;

    monto_descuento_euro = (parseFloat(descuento) / 100) * parseFloat(monto_total_euro);
    monto_final_euro = parseFloat(monto_total_euro) - monto_descuento_euro;

    monto_descuento_peso = (parseFloat(descuento) / 100) * parseFloat(monto_total_peso);
    monto_final_peso = parseFloat(monto_total_peso) - monto_descuento_peso;

    $('.total_carrito_dolar').html('<h5>' + '$' + parseFloat(monto_total_dolar).toFixed(2)) + '</h5>';
    $('.descuento_user_dolar').html('-$' + parseFloat(monto_descuento_dolar).toFixed(2));
    $('.monto_final_dolar').html('$' + parseFloat(monto_final_dolar).toFixed(2));

    $('.total_carrito_peso').html('<h5>' + '$' + parseFloat(monto_total_peso).toFixed(2)) + '</h5>';
    $('.descuento_user_peso').html('-$' + parseFloat(monto_descuento_peso).toFixed(2));
    $('.monto_final_peso').html('$' + parseFloat(monto_final_peso).toFixed(2));

    $('.total_carrito_euro').html('<h5>' + '$' + parseFloat(monto_total_euro).toFixed(2)) + '</h5>';
    $('.descuento_user_euro').html('-$' + parseFloat(monto_descuento_euro).toFixed(2));
    $('.monto_final_euro').html('$' + parseFloat(monto_final_euro).toFixed(2));

    $("#monto_dolar").val(parseFloat(monto_final_dolar).toFixed(2));
    $("#monto_euro").val(parseFloat(monto_final_euro).toFixed(2));
    $("#monto_peso").val(parseFloat(monto_final_peso).toFixed(2));

    idOrden = $("#orden_id").val();
    updateOrdenRespuestos(idOrden, monto_final_dolar, monto_final_euro, monto_final_peso);
}


function makeActions(idPieza) {

    actionMenu =
        '<div class="btn-actions-pane-right actions-icon-btn">'
        + '<div class="btn-group dropdown">'
        + '<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">'
        + '<i class="pe-7s-menu btn-icon-wrapper"></i>'
        + '</button>'

        + '<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">'
        + '<button type="button" tabindex="0" class="dropdown-item showPiezaButton" data-idpieza="'
        + idPieza
        + '">'
        + '<i class="dropdown-icon lnr-inbox"> </i><span>Ver</span>'
        + '</button>'

        + '<button type="button" tabindex="0" class="dropdown-item editPiezaButton" data-idpieza="'
        + idPieza
        + '">'
        + '<i class="dropdown-icon lnr-inbox"> </i><span>Editar</span>'
        + '</button>'

        + '</div>'
        + '</div>'
        + '</div>'
        ;

    return actionMenu;
}

function getPieza(idPieza) {
    resetPiezaDiv();

    var rutaModelos = "/getPieza/" + idPieza;

    $.get(rutaModelos, function (response) {

        /* evalúo si hay precio en la tabla de actualización y en caso contrario tomo el valor original de la tabla de precios  */
        if (response.precio.length > 0)
            precio_fob = response.precio[0].precio_fob;
        else
            precio_fob = response.precio_fob;
        /* ***************************** */

        response.modelo_pieza.forEach(element => {

            $('#modelo_respuestos_onsite_id_div').append(
                '<li>'
                + '<span class="btn btn-success px-2 mt-2 modelo_ver" '
                + 'data-idmodelo='
                + element.modelo_respuestos_onsite.id
                + '>'
                + element.modelo_respuestos_onsite.nombre
                + '</>'
                + '</li>'
            )
        });

        $('#numero_div').html(response.modelo_pieza[0].numero);

        $('#spare_parts_code_div').html(response.spare_parts_code);
        $('#part_name_div').html(response.part_name);
        $('#precio_fob_div').html('$' + parseFloat(precio_fob).toFixed(2));

        $('#peso_div').html(response.peso);
        $('#dimensiones_div').html(response.dimensiones);
        $('#pia_div').html(response.pia);


        $('#description_div').html(response.description);

        /* if (response.part_image == null) {
            $('#part_image_div').html(
                '<img src="'
                + '/imagenes/' + 'default_repuestos.jpg'
                + '"></img>'
            );
        }

        else {
            $('#part_image_div').html(
                '<img src="'
                + '/imagenes/' + response.part_image
                + '"></img>'
            );
        } */


    });

}

function resetPiezaDiv(idPieza) {

    $('#modelo_respuestos_onsite_id_div').html('');
    $('#numero_div').html('');
    $('#spare_parts_code_div').html('');
    $('#part_name_div').html('');
    $('#precio_fob_div').html('');
    $('#peso_div').html('');
    $('#dimensiones_div').html('');
    $('#pia_div').html('');
    $('#description_div').html('');
    /* $('#part_image_div').html(''); */
}

function getPiezaEdit(idPieza) {
    var rutaModelos = "/getPieza/" + idPieza;

    $.get(rutaModelos, function (response) {
        /* evalúo si hay precio en la tabla de actualización y en caso contrario tomo el valor original de la tabla de precios  */
        if (response.precio.length > 0)
            precio_fob = response.precio[0].precio_fob;
        else
            precio_fob = response.precio_fob;
        /* ***************************** */


        $('#spare_parts_code_modal').val(response.spare_parts_code);
        $('#part_name_modal').val(response.part_name);
        $('#description_modal').val(response.description);
        $('#part_image_modal').val(response.part_image);
        $('#precio_fob_modal').val(precio_fob);
        $('#peso_modal').val(response.peso);
        $('#dimensiones_modal').val(response.dimensiones);
        $('#pia_modal').val(response.pia);

        $('#moneda_modal option[value="' + response.moneda + '"]').attr("selected", "selected");

        /* 
        $('#part_image_div').html(
            '<img src="'
            + '/imagenes/' + response.part_image
            + '"></img>'
        ); */

    });

}

function showToast(mensaje, contexto, tipo) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "onclick": true
    }
    if (contexto == 'carrito') {
        toastr.options.onclick = function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#carrito_repuestos").offset().top
            }, 500);
        }
    }

    //toastr["success"]("Repuesto agregado correctamente. Click para ir al carrito", "REPUESTO ONSITE");
    toastr[tipo](mensaje); //se quita el titulo

}


function alertDisabledToast() {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",

    }

    toastr["warning"]("Debe consignar un valor de cantidad", "REPUESTO ONSITE");

}

function recalculateTotal(simbolo) {
    var total = 0;

    let monto_total_dolar = 0;
    let monto_total_euro = 0;
    let monto_total_peso = 0;


    $('.cantidad_footer').map(function () {
        precio = $(this).data('preciofooter');
        cantidad = $(this).val();
        simbolo = $(this).data('simbolo');
        descuento = $('#descuento_user').val();

        if (simbolo == 'USD') {

            monto_dolar = parseInt(cantidad) * parseFloat(precio);
            monto_total_dolar = parseFloat(monto_total_dolar) + parseFloat(monto_dolar);
            monto_descuento_dolar = parseFloat(monto_total_dolar) * (parseFloat(descuento) / 100);
            monto_final_dolar = parseFloat(monto_total_dolar) - parseFloat(monto_descuento_dolar);

        }

        if (simbolo == 'EUR') {

            monto_euro = parseInt(cantidad) * parseFloat(precio);
            monto_total_euro = parseFloat(monto_total_euro) + parseFloat(monto_euro);
            monto_descuento_euro = parseFloat(monto_total_euro) * (parseFloat(descuento) / 100);
            monto_final_euro = parseFloat(monto_total_euro) - parseFloat(monto_descuento_euro);


        }

        if (simbolo == 'AR$') {

            monto_peso = parseInt(cantidad) * parseFloat(precio);
            monto_total_peso = parseFloat(monto_total_peso) + parseFloat(monto_peso);
            monto_descuento_peso = parseFloat(monto_total_peso) * (parseFloat(descuento) / 100);
            monto_final_peso = parseFloat(monto_total_peso) - parseFloat(monto_descuento_peso);


        }


        $('.total_carrito_dolar').html('<h5>' + '$' + parseFloat(monto_total_dolar).toFixed(2)) + '</h5>';
        $('.descuento_user_dolar').html('-$' + parseFloat(monto_descuento_dolar).toFixed(2));
        $('.monto_final_dolar').html('$' + parseFloat(monto_final_dolar).toFixed(2));

        $('.total_carrito_peso').html('<h5>' + '$' + parseFloat(monto_total_peso).toFixed(2)) + '</h5>';
        $('.descuento_user_peso').html('-$' + parseFloat(monto_descuento_peso).toFixed(2));
        $('.monto_final_peso').html('$' + parseFloat(monto_final_peso).toFixed(2));

        $('.total_carrito_euro').html('<h5>' + '$' + parseFloat(monto_total_euro).toFixed(2)) + '</h5>';
        $('.descuento_user_euro').html('-$' + parseFloat(monto_descuento_euro).toFixed(2));
        $('.monto_final_euro').html('$' + parseFloat(monto_final_euro).toFixed(2));

        $("#monto_dolar").val(parseFloat(monto_final_dolar).toFixed(2));
        $("#monto_euro").val(parseFloat(monto_final_euro).toFixed(2));
        $("#monto_peso").val(parseFloat(monto_final_peso).toFixed(2));


    })

    idOrden = $("#orden_id").val();
    updateOrdenRespuestos(idOrden, monto_final_dolar, monto_final_euro, monto_final_peso);
}

function updateDetalleOrdenRespuestos(idDetalleOrden, cantidad, subtotal, subtotal_descuento) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/updateDetalleOrdenRespuestos/' + idDetalleOrden,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            cantidad: cantidad,
            precio_total: subtotal,
            precio_neto: subtotal_descuento
        },
        dataType: 'JSON',
        success: function (data) {
            console.log('update success');
            console.log(data);
        }
    }
    );
}

function updateOrdenRespuestos(idOrden, monto_final_dolar, monto_final_euro, monto_final_peso) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/updateOrdenRespuestos/' + idOrden,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            monto_dolar: monto_final_dolar,
            monto_euro: monto_final_euro,
            monto_peso: monto_final_peso
        },
        dataType: 'JSON',
        success: function (data) {
            console.log('primer actualizacion de la orden....');
            console.log(data);

        }
    }
    );
}

function updateEstadoOrdenRespuestos(idOrden, estado, tipo_mensaje) {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/updateEstadoOrdenRespuestos/' + idOrden,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            estado_id: estado
        },
        dataType: 'JSON',
        success: function (data) {
            showToast('Estado de Orden actualizado correctamente. Orden Nº: '
                + data.id
                + ' Montos: '
                + 'USD: '
                + parseFloat(data.monto_dolar).toFixed(2)
                + '- EUR: '
                + parseFloat(data.monto_euro).toFixed(2)
                + '- AR$: '
                + parseFloat(data.monto_peso).toFixed(2)

                , 'updateEstado', tipo_mensaje);
        }
    }
    );
}

function getUsuarioEmpresa(idEmpresa) {
    var rutaModelos = "/getUsuarioEmpresa/" + idEmpresa;


    $.get(rutaModelos, function (response, state) {
        console.log('length response: ' + response.length);

        $('.usuarioEmpresa').html(
            '<p>'
            + 'Usuario:'
            + '</p>'
            + '<p>'
            + 'Nombre: '
            + response.user_repuestos.name
            + '</p>'
            + '<p>'
            + 'email: '
            + response.user_repuestos.email
            + '</p>'
        );


    });
}

function setVariablesRepuestos(boton, dataSetFooter) {
    idRespuesto = boton.data('idrespuesto');
    part_name = boton.data('part_name');
    numero = boton.data('numero');
    moneda = boton.data('moneda');

    idCategoria = $("#categoria_respuestos_id").val();
    nameCategoria = $("#categoria_respuestos_id option:selected").text();

    idModelo = $("#modelo_respuestos_id").val();
    nameModelo = $("#modelo_respuestos_id option:selected").text();

    spare_parts_code = boton.data('spare_parts_code');
    precio_fob = parseFloat(boton.data('precio_fob')).toFixed(2);

    index = dataSetFooter.length;

    cantidad = $('.input_respuestos' + idRespuesto).val();

    $("#pieza_respuestos_id").val(idRespuesto);


}

function storeUpdateOrdenPedido() {

    /* controla que no se haya generado YA la orden con el primer respuesto insertado */
    idOrden = $("#orden_id").val();
    $("#orden_respuestos_id").val(idOrden);

    if (idOrden == 0) {
        var variablesFormularioOrden = $("#respuestosOnsiteForm").serialize();
        storeOrdenRespuestos(variablesFormularioOrden, dataSetFooter, moneda, idOrden, idRespuesto, cantidad, precio_fob, precio_total, precio_neto);
    }

    else {
        var variablesFormularioDetalleOrden = $("#respuestosOnsiteForm").serialize();
        storeDetalleOrdenRespuestos(variablesFormularioDetalleOrden, dataSetFooter, moneda, idOrden, idRespuesto, cantidad, precio_fob, precio_total, precio_neto);
    }

    showToast('Repuesto agregado correctamente. Click para ir al carrito', 'carrito', 'success');
}

function updatePieza(idPieza) {

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/updatePieza/' + idPieza,
        type: 'POST',
        data: {
            _token: CSRF_TOKEN,
            spare_parts_code: $('#spare_parts_code_modal').val(),
            part_name: $('#part_name_modal').val(),
            description: $('#description_modal').val(),
            /* part_image: $('#part_image_modal').val(), */

            precio_fob: $('#precio_fob_modal').val(),
            peso: $('#peso_modal').val(),
            dimensiones: $('#dimensiones_modal').val(),
            pia: $('#pia_modal').val(),
            moneda: $('#moneda_modal').val()

        },
        dataType: 'JSON',
        success: function (data) {

            showToast('Repuesto actualizado correctamente. Click para ir al carrito', 'carrito', 'success');
            $("#modalEditPieza").modal('toggle');

        },

        fail: function () {
            showToast('No puede actualizarse, reintente.', 'carrito', 'danger');
            $("#modalEditPieza").modal('toggle');
        }

    }
    );


}
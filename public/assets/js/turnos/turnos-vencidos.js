$(document).ready(function () {

    $('input[name="opciones"]').change(function () {
        var opcion = $('input[name="opciones"]:checked').val();

        $("#seccionNuevaFecha").addClass('hidden');
        $("#seccionIngresada").addClass('hidden');
        $("#seccionCancelar").addClass('hidden');
        $('#motivo_cancelacion_turno_id, #hora_turno, #dia_turno').removeAttr('required');

        if (opcion == 'nuevaFecha') {
            $('#hora_turno').prop('required', true);
            $('#dia_turno').prop('required', true);
            $("#seccionNuevaFecha").removeClass('hidden');
            $('#turno_vencido_form').attr('action', '/turnoVencidoActualizar');
        }

        if (opcion == 'ingresado') {
            $("#seccionIngresada").removeClass('hidden');
            $('#turno_vencido_form').attr('action', '/turnoVencidoInformar');
        }

        if (opcion == 'cancelar') {
            $('#motivo_cancelacion_turno_id').prop('required', true);
            $("#seccionCancelar").removeClass('hidden');
            $('#turno_vencido_form').attr('action', '/turnoVencidoCancelar');
        }
    });
    $('input[name="opciones"]').trigger('change');


    $('#dia_turno').change(function() {
        cargarHorasTurno();
    });

    if($('#dia_turno').length) {
        var d = new Date();
        d.setDate(d.getDate() + 7)
        document.getElementById("dia_turno").max = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
    }
});

function cargarHorasTurno() {

    $("#hora_turno").empty();
    $("#hora_turno").attr('disabled', true);
    $("#hora_turno").append("<option selected='selected' value=''>Buscando turnos...</option>");

    var ruta = '/sucursal_horarios';
    var sucursal_id = $("#sucursal_id").val();
    var dia = $("#dia_turno").val();

    $.ajax({
        url: ruta,
        type: 'GET',
        dataType: 'json',
        data: { 'sucursal_id': sucursal_id, 'dia': dia },
        success: function (data) {
            for (var key in data.horarios) {
                var hora = data.horarios[key];
                $("#hora_turno").append("<option value='"+key+"'>"+hora+"</option>");
            }

            if (Object.keys(data.horarios).length > 0) {
                $("#hora_turno").removeAttr('disabled');
                $("#hora_turno").append("<option selected='selected' value=''>Seleccione hora del turno</option>");
            } else {
                $("#hora_turno").append("<option selected='selected' value=''>Sin horarios para esta fecha</option>");
            }

        }
    });	
}
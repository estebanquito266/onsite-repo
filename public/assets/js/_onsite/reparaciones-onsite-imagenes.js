$(document).ready(function () {

    if ($("#agregarImagenOnsite") != null) {
        $('#agregarImagenOnsite').on("click", function () {
            $("#modalImagenOnsite").modal('toggle');
        });
    }

    if ($("#agregarImagenOnsite") != null) {
        $('#cerrarModalImagenOnsite').on("click", function () {
            $("#modalImagenOnsite").modal('toggle');
        });
    }

    $('#formAgregarImagenOnsite').on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('imagen_onsite_archivo', $('#imagen_onsite_archivo')[0].files[0]);
        formData.append('imagen_onsite_tipo_id', $('#imagen_onsite_tipo_id').val());
        formData.append('reparacion_onsite_id', $('#reparacion_onsite_id').val());
        var token = $("input[name=_token]").val();
        tipo = $('#imagen_onsite_archivo')[0].files[0].type;
        tipoimagen = tipo.substring(0, 5);

        $.ajax({
            type: 'POST',
            url: "/reparacionOnsiteAgregarImagenOnsite",
            headers: { 'X-CSRF-TOKEN': token },
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                this.reset();
                $('.encabezado-imagenes').html('<tr><th class="text-center">Archivo</th><th class="text-center">Tipo</th><th>Vista Previa</th><th>Comandos</th></tr>');
                console.log(data);

                if (tipoimagen == 'image') imagentipoarchivo = data.archivo
                else if (tipo == 'application/vnd.ms-excel') imagentipoarchivo = '/imagenes/reparaciones_onsite/excel.png';
                else if (tipo == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') imagentipoarchivo = '/imagenes/reparaciones_onsite/excel.png';
                else if (tipo == 'application/pdf') imagentipoarchivo = '/imagenes/reparaciones_onsite/pdf.png';
                else imagentipoarchivo = '/imagenes/reparaciones_onsite/aplication.png';
                var fila = "<tr id='tr_imagen_onsite_" + data.imagenOnsiteId + "'><td class='text-center'><a href='" + data.archivo + "' class='badge badge-primary' target='_BLANK'>Link</a></td><td class='text-center'>" + '<span class="badge badge-pill badge-warning">' + data.tipoImagen + "</span></td><td><a href='" + data.archivo + "' target='_BLANK'><img src='" + imagentipoarchivo + "' width='100'></a></td><td class='text-right'><button class='btn btn-danger eliminar-imagen-onsite' type='button' data-id='" + data.imagenOnsiteId + "' id='eliminarImagenOnsite" + data.imagenOnsiteId + "'  onclick='eliminarImagen(this);'><i class='fa fa-trash'></i></button></td></tr>";
                $('#tbody_imagenes_onsite').append(fila);
                $("#modalImagenOnsite").modal('toggle');
            },
            error: function (data) {
                console.log(data);
            }
        });
    });


    $('#formAgregarVisita').on("submit", function (e) {
        e.preventDefault();
        var formData = new FormData();
        formData.append('reparacion_onsite_id', $('#reparacion_onsite_id').val());

        var token = $("input[name=_token]").val();

        $.ajax({
            type: 'POST',
            url: "/reparacionOnsiteAgregarVisita",
            headers: { 'X-CSRF-TOKEN': token },
            dataType: 'json',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                //this.reset();
                console.log('formAgregarVisita - success'); 
                console.log(data);               
                $("#modalAgregarVisita").modal('toggle');
                location.reload();
            },
            error: function (data) {
                console.log('formAgregarVisita - error'); 
                console.log(data);
            }
        });
    });

});

function eliminarImagen(e) {
    var id = e.dataset["id"];
    var token = $("input[name=_token]").val();

    $.ajax(
        {
            url: "/reparacionOnsiteEliminarImagenOnsite/" + id,
            type: 'DELETE',
            data: {
                "id": id,
                "_token": token,
            },
            success: function () {
                $('#tr_imagen_onsite_' + id).hide('slow');
            }
        });
}

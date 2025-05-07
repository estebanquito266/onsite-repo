function obrasConSistemas(idObra) {

    getObraConSistema(idObra).then(response => {

        $("#sistema_onsite_id").html('');

        if (response.length <= 0)
            $("#sistema_onsite_id").append("<option selected='selected' value=''>Modelos no encontradas</option>");
        else {

            for (i = 0; i < response.length; i++) {
                $("#sistema_onsite_id").append("<optgroup label='Obra: " + response[i].nombre + "' >");
                
                
                if (response[i].sistema_onsite.length > 0) {

                    for (j = 0; j < response[i].sistema_onsite.length; j++) {
                        $("#sistema_onsite_id").append(
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

        };
       


    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}


function sistemasConSolicitudes(idSistemas) {

    getSolicitudesPorSistema(idSistemas).then(response => {

        $("#solicitud_id").html('');

        if (response.length <= 0)

            $("#solicitud_id").append("<option selected='selected' value=''>Solicitudes no encontradas</option>");

        else {
            
            $("#solicitud_id").append('<option value="">Seleccione una solicitud</option>');
            for (i = 0; i < response.length; i++) {
                $("#solicitud_id").append("<optgroup label='Sistema: " + response[i].nombre + "' >");
                
                
                if (response[i].solicitud_onsite.length > 0) {

                    for (j = 0; j < response[i].solicitud_onsite.length; j++) {
                        
                        $("#solicitud_id").append(
                            "<option value="
                            + response[i].solicitud_onsite[j].id
                            + " data-idsistema="
                            + response[i].solicitud_onsite[j].sistema_onsite_id
                            + "'>"
                            + "[ID:"+response[i].solicitud_onsite[j].id+'] '
                            +(response[i].solicitud_onsite[j].tipo ? response[i].solicitud_onsite[j].tipo.nombre : '-')
                            + "</option>");
                    }
                }
            };

        };
       


    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}
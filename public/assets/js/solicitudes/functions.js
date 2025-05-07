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
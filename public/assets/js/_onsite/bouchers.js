$(function () {

    $('#empresa_instaladora_id').on('change', function () {
        idEmpresa = $(this).find(':selected').val();
        getObrasPorEmpresa(idEmpresa);
      });
    


});


function getObrasPorEmpresa(idEmpresa) {
    var rutaModelos = "/getObrasPorEmpresa/" + idEmpresa;
  
  
    $.get(rutaModelos, function (response) { 
  
      $("#obra_onsite_id").html('');
  
      if (response.length <= 0)
        $("#obra_onsite_id").append("<option selected='selected' value=''>Obras no encontradas</option>");
      else {
        $("#obra_onsite_id").append("<option selected='selected' value=''>Seleccione el obra - </option>");
  
        for (i = 0; i < response.length; i++) {
  
  
              $("#obra_onsite_id").append(
                "<option value="
                + response[i].id
                + " data-idobra="
                + response[i].obra_onsite_id
                + " data-nombre_sistema='"
                + response[i].sistema_onsite.nombre
                + "'>"
                + response[i].nombre              
                + "</option>");
          
        };
  
      };
  
  
    });
  }
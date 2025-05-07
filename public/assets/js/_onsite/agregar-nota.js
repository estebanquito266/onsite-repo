$(document).ready(function () {
  
  $('button[name="agregarNota"]').click(function (event) {
    $("#reparacion_onsite_id").val($(this).val());
    $("#nota").val('');
  })

  $("#guardarNota").click(function (event) {
    
    event.preventDefault();

    var datos = $("#formNota").serialize();
    var route = "/agregarNota";

    $.ajax({
      url: route,
      type: 'POST',
      dataType: 'json',
      data: datos,

      success: function () {
        $("#modalAgregarNota").modal('toggle');
      },

    });
  });

});
document.addEventListener("DOMContentLoaded", function (event) {


  $(document).ready(function () {

    var ticket_id = $('#ticket_id').val();
    $('.select_usuario_grupo').hide();
    if(ticket_id>0){
      $('.select_usuario_grupo').show();
      $('#radio_usuario').hide();
      $('#radio_grupo').hide();
      var user_id = $('#user_id').val();
      var group_user_receiver_id = $('#group_user_receiver_id').val();

      if(user_id.length > 0){
        $('#radio_usuario').show();
      }

      if(group_user_receiver_id.length){
        $('#radio_grupo').show();
      }

    }
    
    $('.cliente_grupo').click(function () {
      var id_to_show = $(this).children('input').first().data('id');
      $('.select_usuario_grupo').hide();
      $('#' + id_to_show).show();
    });

    $('#group_user_receiver_id').on('select2:select', function (e) { 
      $('#user_id').val(null).trigger("change");
    });

    $('#user_id').on('select2:select', function (e) { 
        $('#group_user_receiver_id').val(null).trigger("change");
    });



  });



});

$(document).ready(function () {

  $("#botonEliminarTicket").click(function () {
    $("#divEliminarTicket").removeClass("d-none");
  });

  $("#botonEliminarTicketNo").click(function () {
    $("#divEliminarTicket").addClass("d-none");
  });

  //Al cargar la pagina establece valores default dependiendo del valor del toggle
  var tipo_ticket = $('select[id=tipo_ticket] option').filter(':selected').val();
  $('#type').val(tipo_ticket);
  if (tipo_ticket == 1) {
    $('#div_buscar_cliente_reparacion').show();
    $('#div_buscar_cliente_derivacion').hide();
    $('#ticket_reparacion_id_create').show();
    $('#ticket_derivacion_id_create').hide();
    $('#div_cliente_id').show();
    $('#ticket_derivacion_id_create').prop('required', true);
  } else if (tipo_ticket == 2) {
    $('#div_buscar_cliente_reparacion').hide();
    $('#div_buscar_cliente_derivacion').show();
    $('#ticket_reparacion_id_create').hide();
    $('#ticket_derivacion_id_create').show();
    $('#div_cliente_id').show();
    $('#ticket_derivacion_id_create').prop('required', false);
  } else {
    $('#div_buscar_cliente_reparacion').hide();
    $('#div_buscar_cliente_derivacion').hide();
    $('#ticket_reparacion_id_create').hide();
    $('#ticket_derivacion_id_create').hide();
    $('#div_cliente_id').hide();
    $('#ticket_derivacion_id_create').prop('required', false);
  }

  $("#priority_ticket_id, #status_ticket_id").select2({
    theme: "bootstrap4",
    placeholder: "Selecciona una opción",
  });
  //aplicacion select2 para los menu desplegables
  $("#motivo_consulta_ticket_id, #category_ticket_id, #user_id, #group_user_receiver_id, #cliente_id").select2({
    theme: "bootstrap4",
    placeholder: "Selecciona una opción",
  });

  if ($("#fieldset_setup").val() == "disable_fields") { //Chequeo el valor del input para deshabilitar los campos del formulario (show/edit)
    $("#comentariosForm").show();

    var formulario = $("#ticketForm");
    var formularioComments = $("#comentariosBody");


    formulario.find(':input').each(function () {//Obtengo los campos del formulario para inhabilitarlos
      // deshabilito los campos
      $(this).prop('disabled', true);
    });

    formularioComments.find(':input').each(function () {//Obtengo los campos del formulario para inhabilitarlos
      // deshabilito los campos
      $(this).prop('disabled', true);
    });
  }


});
//Habilita / Inhabilita campos al cambiar el valor del toggle 
$('#tipo_ticket').on('change', function () {
  var tipo_ticket = $('select[id=tipo_ticket] option').filter(':selected').val();
  $('#type').val(tipo_ticket);
  console.log(tipo_ticket);
  if (tipo_ticket == 1) {
    $('#div_buscar_cliente_reparacion').show();
    $('#div_buscar_cliente_derivacion').hide();
    $('#ticket_reparacion_id_create').show();
    $('#ticket_derivacion_id_create').hide();
    $('#div_cliente_id').show();
    $('#ticket_derivacion_id_create').prop('required', 'enabled');
  } else if (tipo_ticket == 2) {
    $('#div_buscar_cliente_reparacion').hide();
    $('#div_buscar_cliente_derivacion').show();
    $('#ticket_reparacion_id_create').hide();
    $('#ticket_derivacion_id_create').show();
    $('#div_cliente_id').show();
    $('#ticket_derivacion_id_create').prop('required', 'disabled');
  } else {
    $('#div_buscar_cliente_reparacion').hide();
    $('#div_cliente_id').hide();
    $('#div_buscar_cliente_derivacion').hide();
    $('#ticket_reparacion_id_create').hide();
    $('#ticket_derivacion_id_create').hide();
    $('#ticket_derivacion_id_create').prop('required', false);
  }
});



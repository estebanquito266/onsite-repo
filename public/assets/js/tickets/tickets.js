$(document).ready(function() {
 
      function removeColumnaElimiminarComments(){
        // Selecciona la última columna en el encabezado, el foot y el cuerpo de la tabla
        var $headerColumn = $('#comentariosList table thead tr th:last');
        var $bodyColumns = $('#comentariosList table tbody tr td:last');
        var $footerColumn = $('#comentariosList table tfoot tr th:last');

        $headerColumn.hide();
        $footerColumn.hide();
        $bodyColumns.hide();
      }

      function setCamposOnCreate() { // Establece los valores al momento de abrir la ventana modal para creacion de ticket
        $("#div_comment_section").hide();
        $('#ticket_editar_btn_show').hide();
        //Ocultar Campos Show
        $("#ticket_reason_ticket_id_show").hide();
        $("#ticket_category_ticket_id_show").hide();
        $("#ticket_user_receiver_id_show").hide();
        $("#ticket_group_user_receiver_id_show").hide();
        $("#ticket_detalle_show").hide();
        $("#ticket_expiration_date_show").hide();
        $("#ticket_priority_ticket_id_show").hide();
        $("#ticket_status_ticket_id_show").hide();
        $("#ticket_created_at_show").hide();
        $('#ticket_file_show').hide();
        $("#ticket_user_owner_id_show").hide();

        //Mostrar Campos Create
        $("#ticket_form_button").show();
        $("#ticket_reason_ticket_id_create").show();
        $("#ticket_category_ticket_id_create").show();
        $("#ticket_user_receiver_id_create").show();
        $("#ticket_group_user_receiver_id_create").show();
        $("#ticket_detalle_create").show();
        $("#ticket_priority_ticket_id_create").show();
        $("#ticket_status_ticket_id_create").show();
        $('#ticket_file_create').show();

        $('#ticket_cliente_show #cliente_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_reparacion_id_show #reparacion_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_derivacion_id_show #derivacion_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_detalle_show #descripcion').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_reason_ticket_id_show #motivo_consulta_ticket_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_category_ticket_id_show #category_ticket_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_group_user_receiver_id_show #group_user_receiver_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_user_receiver_id_show #user_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_priority_ticket_id_show #priority_ticket_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);
        $('#ticket_status_ticket_id_show #status_ticket_id').val('').prop('disabled', true).removeAttr("name").prop('required', false);

      }
      function setCamposOnShow(response) { // Establece los valores al momento de abrir la ventana modal para visualizar un ticket
        //Ticket Mostrar Campos Show
        removeColumnaElimiminarComments();
        if(response.priority_ticket_id){
          $("#ticket_priority_ticket_id_show").show();
        }else{
          $("#ticket_priority_ticket_id_show").hide();
        }
        if(response.status_ticket_id){
          $("#ticket_status_ticket_id_show").show();
        }else{
          $("#ticket_status_ticket_id_show").hide();
        }
        if(response.group_user_receiver_id){
          $("#ticket_group_user_receiver_id_show").show();
        }else{
          $("#ticket_group_user_receiver_id_show").hide();
        }
        if(response.user_receiver_id){
          $("#ticket_user_receiver_id_show").show();
        }else{
          $("#ticket_user_receiver_id_show").hide();
        }
        if(response.detail){
          $("#ticket_detalle_show").show();
        }else{
          $("#ticket_detalle_show").hide();
        }
        if(response.expiration_date){
          $("#ticket_expiration_date_show").show();
        }else{
          $("#ticket_expiration_date_show").hide();
        }
        $("#ticket_created_at_show").show();
        if(response.file){
          $('#ticket_file_show').show();
        }else{
          $('#ticket_file_show').hide();
        }


        created_at = moment(response.created_at);
        $("#ticket_created_at_show #created_at").val(created_at.format("DD/MM/YYYY"));

        $('#ticket_editar_btn_show').hide();
        $("#ticket_form_button").hide();
        $("#ticket_reason_ticket_id_show").show();
        $("#ticket_category_ticket_id_show").show();
        $("#ticket_user_owner_id_show").show();
        
        //Ocultar Campos Create
        $('#ticket_file_create').hide();
        $("#ticket_reason_ticket_id_create").hide();
        $("#ticket_category_ticket_id_create").hide();
        $("#ticket_user_receiver_id_create").hide();
        $("#ticket_group_user_receiver_id_create").hide();
        $("#ticket_detalle_create").hide();
        $("#ticket_priority_ticket_id_create").hide();
        $("#ticket_status_ticket_id_create").hide();
      }

      /****************************************** INICIO TICKET REPARACION ************************************************/
      function setCamposDefaultReparacion() { // Establece los valores al momento de abrir la ventana modal en index Reparacion
        var tokenAux = $("[name='_token']").val();
        var userOwnerAux = $("[name='user_owner_id']").val();
        var varModalTicket = $("[name='_modalTicket']").val();
        var varModalComment = $("[name='_modalComment']").val();
        $('#div_modal_campos').show();
        
        $("#modalCrearTicket input, #modalCrearTicket select, #modalCrearTicket textarea").val(""); //Vacio campos para nuevo uso
        $("#ticket_cliente_create, ticket_cliente_show").hide();
        $("#modalCrearTicket select").prop("selectedIndex", 0);
        $('#ticket_derivacion_id_create').html('');
        $('#ticket_derivacion_id_show').html('');
        $("#ticket_expiration_date_create").html('');
        $('#ticket_reparacion_id_create, #ticket_reparacion_id_show').hide();
        //Reasigna los valores que se borraron al cargar la ventana modal
        $("[name='_token']").val(tokenAux);
        $("[name='user_owner_id']").val(userOwnerAux);
        $("[name='_modalTicket']").val(varModalTicket);
        $("[name='_modalComment']").val(varModalComment);
        $("[name='type']").val(1);
        //Copia el contenido de la div usado para el create, en la usada para el show
        $("#ticket_reason_ticket_id_show").html($("#ticket_reason_ticket_id_create").html());
        $("#ticket_reparacion_id_show").html( $("#ticket_reparacion_id_create").html());
        $("#ticket_category_ticket_id_show").html($("#ticket_category_ticket_id_create").html());
        $("#ticket_user_receiver_id_show").html($("#ticket_user_receiver_id_create").html());
        $("#ticket_group_user_receiver_id_show").html($("#ticket_group_user_receiver_id_create").html());
        $("#ticket_detalle_show").html( $("#ticket_detalle_create").html());
        $("#ticket_priority_ticket_id_show").html($("#ticket_priority_ticket_id_create").html());
        $("#ticket_status_ticket_id_show").html($("#ticket_status_ticket_id_create").html());

      }
      function setCamposDefaultReparacionList() { // Establece los valores al momento de abrir la ventana modal en index Reparacion
        var tokenAux = $("[name='_token']").val();
        var userOwnerAux = $("[name='user_owner_id']").val();
        var varModalTicket = $("[name='_modalTicket']").val();
        var varModalComment = $("[name='_modalComment']").val();
        $('#div_modal_campos').hide();
        $('#div_comment_section').hide();
        
        $("#modalCrearTicket input, #modalCrearTicket select, #modalCrearTicket textarea").val(""); //Vacio campos para nuevo uso
        $("#ticket_cliente_create, ticket_cliente_show").hide();
        $("#modalCrearTicket select").prop("selectedIndex", 0);
        $('#ticket_derivacion_id_create').html('');
        $('#ticket_derivacion_id_show').html('');
        $("#ticket_expiration_date_create").html('');
        $('#ticket_reparacion_id_create, #ticket_reparacion_id_show').hide();
        //Reasigna los valores que se borraron al cargar la ventana modal
        $("[name='_token']").val(tokenAux);
        $("[name='user_owner_id']").val(userOwnerAux);
        $("[name='_modalTicket']").val(varModalTicket);
        $("[name='_modalComment']").val(varModalComment);
        $("[name='varType']").val(2);
        
        //Copia el contenido de la div usado para el create, en la usada para el show
        $("#ticket_reason_ticket_id_show").html($("#ticket_reason_ticket_id_create").html());
        $("#ticket_reparacion_id_show").html( $("#ticket_reparacion_id_create").html());
        $("#ticket_category_ticket_id_show").html($("#ticket_category_ticket_id_create").html());
        $("#ticket_user_receiver_id_show").html($("#ticket_user_receiver_id_create").html());
        $("#ticket_group_user_receiver_id_show").html($("#ticket_group_user_receiver_id_create").html());
        $("#ticket_detalle_show").html( $("#ticket_detalle_create").html());
        $("#ticket_priority_ticket_id_show").html($("#ticket_priority_ticket_id_create").html());
        $("#ticket_status_ticket_id_show").html($("#ticket_status_ticket_id_create").html());
      }

     //Nuevo Ticket Reparacion
     $('.btn-find-ticket').on('click', function() {//Metodo para la carga del modal ticket para Reparacion
      var reparacionid = $(this).data('reparacion-id');
      var reparacionUserId = $(this).data('user-id');
      var userId = $(this).data('logged-user-id');
      var clienteId = $(this).data('cliente-id');
      setCamposDefaultReparacion();
      $('#modalLabelAgregarTicket').show();
      $('#modalLabelEditarTicket').hide();
      $('#ticket_reparacion_id_create #reparacion_id').val(reparacionid);
      $('#ticket_cliente_create #cliente_id').val(clienteId);
      $('#user_id').val(reparacionUserId);
      $('#listadoTickets').hide();
      setCamposOnCreate();
                      
    });
    
    $('.btn-find-ticket3').on('click', function() {//Metodo para la carga del modal ticket para Reparacion
      var reparacionId = $(this).data('reparacion-id');
      setCamposDefaultReparacionList();
      $.ajax({
          url: '/findTicketsByReparacionId',
          type: 'GET',
          data: { reparacionId: reparacionId },
          success: function(response) {
              if (typeof response === "object" && Object.keys(response).length === 0) { //Si retorno un array vacio, es un nuevo ticket
                
              }else { //Si retorno un array con datos, es un ticket existente
                  console.log(response);
                  setCamposOnShow(response);

                  $('#listadoTickets').show();
                  
                  $('#modalLabelAgregarTicket').hide();
                  $('#modalLabelEditarTicket').show();

                  $("#tickets_list").empty();
                  tickets = response;
                  if (response.length <= 0){
                    $("#tickets_list").append("<option selected='selected' value=''>No se han encontrado Tickets</option>");
                    $('#div_modal_campos').hide();
                  }
                  else{
                    $("#tickets_list").append("<option selected='selected' value=''>Seleccione un Ticket - </option>");
                    $('#div_modal_campos').hide();
                  }
                      
                  for (i = 0; i < response.length; i++) {
                      $("#tickets_list").append("<option value='" + response[i].id + "'> Ticket [" + response[i].id + "] </option>");
                  }
               
                }                                
          },
          error: function(xhr, status, error) {
              alert("error");
              console.error(error);
          }
      });
  });

    $('.btn-find-ticket5').on('click', function() {//Metodo para la carga del modal ticket para Reparacion (1 solo ticket + comments)
      var ticketId = $(this).data('ticket-id');
      setCamposDefaultReparacionList();
      $.ajax({
        url: '/findTicketById',
        type: 'GET',
        data: { ticketId: ticketId },
        success: function(response) {
          setCamposOnShow(response);

          $('#listadoTickets').hide();
          $('#comentariosForm').show();
          
          $('#modalLabelAgregarTicket').hide();
          $('#modalLabelEditarTicket').show();

          $("#tickets_list").empty();
             
          const expirationDate = moment(response.expiration_date);

          if(response.user_owner_id==$('#user_owner_id').val()){
            $('#ticket_editar_btn_show').show();
            $('button:contains("Cerrado")').prop('disabled', false);
          }else{
            $('#ticket_editar_btn_show').hide();
            $('button:contains("Cerrado")').prop('disabled', true);
          }
          $('#modalLabelEditarTicket').html('<a href="#" id="urlTicketShow" class="search-button">' +
                                            '<i class="fas fa-search search-icon"></i>Ticket' +
                                            '</a> - Detalles Ticket');
          $('#ticket_expiration_date_show #expiration_date').val(expirationDate.format("DD/MM/YYYY"));
          $('#urlTicketShow').attr('href','/ticket/'+response.id);
          $('#editar_btn').attr('href','/ticket/'+response.id+'/edit');
          if(response.file){
            $('#file_download').attr('href','/files/'+response.file);
          }
          $("#user_owner").val(response.user_owner_name);
          $('#ticket_cliente_show #cliente_id').val(response.cliente_derivacion_id).removeAttr("name");
          $('#ticket_derivacion_id_show #derivacion_id').val(response.derivacion_id).removeAttr("name");
          $('#ticket_detalle_show #descripcion').val(response.detail).prop('disabled',true).removeAttr("name");
          $('#ticket_reason_ticket_id_show #motivo_consulta_ticket_id').val(response.reason_ticket_id).prop('disabled',true).removeAttr("name");
          $('#ticket_category_ticket_id_show #category_ticket_id').val(response.category_ticket_id).prop('disabled',true).removeAttr("name");
          $('#ticket_group_user_receiver_id_show #group_user_receiver_id').val(response.group_user_receiver_id).prop('disabled',true).removeAttr("name");
          

          if($('#ticket_user_receiver_id_show #user_id option[value=' + response.user_receiver_id + ']').length === 0 && response.user_receiver_id > 0) {
              $('#ticket_user_receiver_id_show #user_id').append('<option value="' + response.user_receiver_id + '">['+response.user_receiver_id+'] ' + response.user_receiver.name + '</option>');
          }

          console.log(response, "asdasdasd");
          $('#ticket_user_receiver_id_show #user_id').val(response.user_receiver_id).prop('disabled',true).removeAttr("name");

          $('#ticket_priority_ticket_id_show #priority_ticket_id').val(response.priority_ticket_id).prop('disabled',true).removeAttr("name");
          $('#ticket_status_ticket_id_show #status_ticket_id').val(response.status_ticket_id).prop('disabled',true).removeAttr("name");
          $("#div_comment_section").show();
          $('#div_modal_campos').show();
          getComentarios(response.id,response.user_owner_id);
                  
        },
        error: function(xhr, status, error) {
            alert("error");
            console.error(error);
        }
        
    })});
      
      

/****************************************** FIN TICKET REPARACION ************************************************/

/****************************************** INICIO TICKET DERIVACION ************************************************/
    function setCamposDefaultDerivacion() { // Establece los valores al momento de abrir la ventana modal en Ticket Derivacion
      var tokenAux = $("[name='_token']").val();
      var userOwnerAux = $("[name='user_owner_id']").val();
      var varModalTicket = $("[name='_modalTicket']").val();
      var varModalComment = $("[name='_modalComment']").val();
      $('#div_modal_campos').show();

      $("#modalCrearTicket input, #modalCrearTicket select, #modalCrearTicket textarea").val(""); //Vacio campos para nuevo uso
      $("#ticket_cliente_create, ticket_cliente_show").hide();
      $("#modalCrearTicket select").prop("selectedIndex", 0);
      $('#ticket_reparacion_id_create').html('');
      $('#ticket_reparacion_id_show').html('');
      $("#ticket_expiration_date_create").html('');
      $('#ticket_derivacion_id_create, #ticket_derivacion_id_show').hide();
      //Reasigna los valores que se borraron al cargar la ventana modal
      $("[name='_token']").val(tokenAux);
      $("[name='user_owner_id']").val(userOwnerAux);
      $("[name='_modalTicket']").val(varModalTicket);
      $("[name='_modalComment']").val(varModalComment);
      $("[name='type']").val(2);
      
      //Copia el contenido de la div usado para el create, en la usada para el show
      $("#ticket_reason_ticket_id_show").html($("#ticket_reason_ticket_id_create").html());
      $("#ticket_derivacion_id_show").html( $("#ticket_derivacion_id_create").html());
      $("#ticket_category_ticket_id_show").html($("#ticket_category_ticket_id_create").html());
      $("#ticket_user_receiver_id_show").html($("#ticket_user_receiver_id_create").html());
      $("#ticket_group_user_receiver_id_show").html($("#ticket_group_user_receiver_id_create").html());
      $("#ticket_detalle_show").html( $("#ticket_detalle_create").html());
      $("#ticket_priority_ticket_id_show").html($("#ticket_priority_ticket_id_create").html());
      $("#ticket_status_ticket_id_show").html($("#ticket_status_ticket_id_create").html());

    }

    function setCamposDefaultDerivacionList() { // Establece los valores al momento de abrir la ventana modal en index Derivacion
      var tokenAux = $("[name='_token']").val();
      var userOwnerAux = $("[name='user_owner_id']").val();
      var varModalTicket = $("[name='_modalTicket']").val();
      var varModalComment = $("[name='_modalComment']").val();
      $('#div_modal_campos').hide();
      $('#div_comment_section').hide();
      
      $("#modalCrearTicket input, #modalCrearTicket select, #modalCrearTicket textarea").val(""); //Vacio campos para nuevo uso
      $("#ticket_cliente_create, ticket_cliente_show").hide();
      $("#modalCrearTicket select").prop("selectedIndex", 0);
      $('#ticket_reparacion_id_create').html('');
      $('#ticket_reparacion_id_show').html('');
      $("#ticket_expiration_date_create").html('');
      $('#ticket_derivacion_id_create, #ticket_derivacion_id_show').hide();
      //Reasigna los valores que se borraron al cargar la ventana modal
      $("[name='_token']").val(tokenAux);
      $("[name='user_owner_id']").val(userOwnerAux);
      $("[name='_modalTicket']").val(varModalTicket);
      $("[name='_modalComment']").val(varModalComment);
      $("[name='varType']").val(2);
      
      //Copia el contenido de la div usado para el create, en la usada para el show
      $("#ticket_reason_ticket_id_show").html($("#ticket_reason_ticket_id_create").html());
      $("#ticket_derivacion_id_show").html( $("#ticket_derivacion_id_create").html());
      $("#ticket_category_ticket_id_show").html($("#ticket_category_ticket_id_create").html());
      $("#ticket_user_receiver_id_show").html($("#ticket_user_receiver_id_create").html());
      $("#ticket_group_user_receiver_id_show").html($("#ticket_group_user_receiver_id_create").html());
      $("#ticket_detalle_show").html( $("#ticket_detalle_create").html());
      $("#ticket_priority_ticket_id_show").html($("#ticket_priority_ticket_id_create").html());
      $("#ticket_status_ticket_id_show").html($("#ticket_status_ticket_id_create").html());
    }

      //Nuevo Ticket Derivacion
      $('.btn-find-ticket1').on('click', function() {//Metodo para la carga del modal ticket para Derivacion
        var derivacionId = $(this).data('derivacion-id');
        var derivacionUserId = $(this).data('user-id');
        var clienteDerivacionId = $(this).data('cliente-id');
        
        setCamposDefaultDerivacion();
       
        $('#modalLabelAgregarTicket').show();
        $('#modalLabelEditarTicket').hide();
        $('#ticket_derivacion_id_create #derivacion_id').val(derivacionId);
        $('#ticket_cliente_create #cliente_id').val(clienteDerivacionId);
        $('#ticket_user_receiver_id_create #user_id').val(derivacionUserId);
        $('#listadoTickets').hide();
        setCamposOnCreate();
      });
      
      //Ticket Existente Derivacion
      $('.btn-find-ticket2').on('click', function() {//Metodo para la carga del modal ticket para Derivacion
        var derivacionId = $(this).data('derivacion-id');
        setCamposDefaultDerivacionList();
        $.ajax({
            url: '/findTicketsByDerivacionId',
            type: 'GET',
            data: { derivacionId: derivacionId },
            success: function(response) {
                if (typeof response === "object" && Object.keys(response).length === 0) { //Si retorno un array vacio, es un nuevo ticket
                  
                }else { //Si retorno un array con datos, es un ticket existente
                    console.log(response);
                    setCamposOnShow(response);

                    $('#listadoTickets').show();
                    
                    $('#modalLabelAgregarTicket').hide();
                    $('#modalLabelEditarTicket').show();

                    $("#tickets_list").empty();
                    tickets = response;
                    if (response.length <= 0){
                      $("#tickets_list").append("<option selected='selected' value=''>No se han encontrado Tickets</option>");
                      $('#div_modal_campos').hide();
                    }
                    else{
                      $("#tickets_list").append("<option selected='selected' value=''>Seleccione un Ticket - </option>");
                      $('#div_modal_campos').hide();
                    }
                        
                    for (i = 0; i < response.length; i++) {
                        $("#tickets_list").append("<option value='" + response[i].id + "'> Ticket [" + response[i].id + "] </option>");
                    }
                 
                  }                                
            },
            error: function(xhr, status, error) {
                alert("error");
                console.error(error);
            }
        });
    });

    $('.btn-find-ticket6').on('click', function() {//Metodo para la carga del modal ticket para Derivacion (1 solo ticket + comments)
      var ticketId = $(this).data('ticket-id');
      setCamposDefaultDerivacionList();
      $.ajax({
        url: '/findTicketById',
        type: 'GET',
        data: { ticketId: ticketId },
        success: function(response) {
          setCamposOnShow(response);
          $('#listadoTickets').hide();
          $('#comentariosForm').show();
          
          $('#modalLabelAgregarTicket').hide();
          $('#modalLabelEditarTicket').show();

          $("#tickets_list").empty();
             
          const expirationDate = moment(response.expiration_date);

          if(response.user_owner_id==$('#user_owner_id').val()){
            $('#ticket_editar_btn_show').show();
            $('button:contains("Cerrado")').prop('disabled', false);
          }else{
            $('#ticket_editar_btn_show').hide();
            $('button:contains("Cerrado")').prop('disabled', true);
          }
          $('#modalLabelEditarTicket').html('<a href="#" id="urlTicketShow" class="search-button">' +
                                            '<i class="fas fa-search search-icon"></i>Ticket' +
                                            '</a> - Detalles Ticket');
          $('#ticket_expiration_date_show #expiration_date').val(expirationDate.format("DD/MM/YYYY"));
          $('#urlTicketShow').attr('href','/ticket/'+response.id);
          $('#editar_btn').attr('href','/ticket/'+response.id+'/edit');
          if(response.file){
            $('#file_download').attr('href','/files/'+response.file);
          }
          $("#user_owner").val(response.user_owner_name);
          $('#ticket_cliente_show #cliente_id').val(response.cliente_derivacion_id).removeAttr("name");
          $('#ticket_derivacion_id_show #derivacion_id').val(response.derivacion_id).removeAttr("name");
          $('#ticket_detalle_show #descripcion').val(response.detail).prop('disabled',true).removeAttr("name");
          $('#ticket_reason_ticket_id_show #motivo_consulta_ticket_id').val(response.reason_ticket_id).prop('disabled',true).removeAttr("name");
          $('#ticket_category_ticket_id_show #category_ticket_id').val(response.category_ticket_id).prop('disabled',true).removeAttr("name");
          $('#ticket_group_user_receiver_id_show #group_user_receiver_id').val(response.group_user_receiver_id).prop('disabled',true).removeAttr("name");
          
          if($('#ticket_user_receiver_id_show #user_id option[value=' + response.user_receiver_id + ']').length === 0 && response.user_receiver_id.length > 0) {
              $('#ticket_user_receiver_id_show #user_id').append('<option value="' + response.user_receiver_id + '">['+response.user_receiver_id+'] ' + response.user_receiver.name + '</option>');
          }

          $('#ticket_user_receiver_id_show #user_id').val(response.user_receiver_id).prop('disabled',true).removeAttr("name");
          $('#ticket_priority_ticket_id_show #priority_ticket_id').val(response.priority_ticket_id).prop('disabled',true).removeAttr("name");
          $('#ticket_status_ticket_id_show #status_ticket_id').val(response.status_ticket_id).prop('disabled',true).removeAttr("name");
          $("#div_comment_section").show();
          $('#div_modal_campos').show();
          getComentarios(response.id,response.user_owner_id);
         
         
        },
        error: function(xhr, status, error) {
            alert("error");
            console.error(error);
        }
        
    })});
/****************************************** FIN TICKET DERIVACION ************************************************/

/****************************************** INICIO TICKET COMENTARIOS ************************************************/
      function getComentarios(ticketId, userId){
        $.ajax({
          url: '/findCommentsByTicketId',
          type: 'GET',
          data: { ticketId: ticketId },
          success: function(response) {
            console.log(response);
            
            $('#comment_user_id').val(userId);
            $('#comment_ticket_id').val(ticketId);
            var comentariosBody = $('#comentariosBody'); // Obtiene el cuerpo de la tabla          
            comentariosBody.empty(); // Vacia el cuerpo de la tabla antes de agregar nuevos datos
            if (typeof response === "object" && Object.keys(response).length === 0) {
              var fila = '<tr><td>No se encontraron resultados</td></tr>';
              comentariosBody.append(fila);
            }else{
              // Recorrer los comentarios y agregar las filas al cuerpo de la tabla
              $.each(response, function(index, comentario) {
                var fila = '<tr>' +
                  '<td>' + comentario.id + '</td>' +
                  '<td>' + comentario.name + '</td>' +
                  '<td>' + comentario.comment + '</td>' +
                  '<td>';
                if (comentario.file != "") {
                  fila += '<a download="' + comentario.file + '" href="/files/' + comentario.file + '" title="' + comentario.file + '">' + comentario.file + '</a>';
                } else {
                  fila += '<p>No se han encontrado archivos</p>';
                }
                fila += '</td>' +
                  '<td> - </td>' +
                  '</tr>';
  
                comentariosBody.append(fila);
              });
            }
            
              },
              error: function(xhr, status, error) {
                  alert("error");
                  console.error(error);
              }
          })};
/****************************************** FIN TICKET COMENTARIOS ************************************************/

          $('#tickets_list').on('change', function() { //Cargar dinamicamente los campos del ticket seleccionado
            ticketId = $(this).val();
            if(!ticketId){
              setCamposDefaultDerivacionList(); //TODO AJUSTAR
              return;
            }

            $.ajax({
              url: '/findTicketById',
              type: 'GET',
              data: { ticketId: ticketId },
              success: function(response) {
                setCamposOnShow(response);
                const expirationDate = moment(response.expiration_date);
                if(response.user_owner_id==$('#user_owner_id').val()){
                  $('#ticket_editar_btn_show').show();
                  $('button:contains("Cerrado")').prop('disabled', false);
                }else{
                  $('#ticket_editar_btn_show').hide();
                  $('button:contains("Cerrado")').prop('disabled', true);
                }
                $('#ticket_expiration_date_show #expiration_date').val(expirationDate.format("DD/MM/YYYY"));
                $('#urlTicketShow').attr('href','/ticket/'+response.id);
                $('#editar_btn').attr('href','/ticket/'+response.id+'/edit');
                if(response.file){
                  $('#file_download').attr('href','/files/'+response.file);
                }
                $("#user_owner").val(response.user_owner_name);
                $('#ticket_cliente_show #cliente_id').val(response.cliente_derivacion_id).removeAttr("name");
                $('#ticket_derivacion_id_show #derivacion_id').val(response.derivacion_id).removeAttr("name");
                $('#ticket_detalle_show #descripcion').val(response.detail).prop('disabled',true).removeAttr("name");
                $('#ticket_reason_ticket_id_show #motivo_consulta_ticket_id').val(response.reason_ticket_id).prop('disabled',true).removeAttr("name");
                $('#ticket_category_ticket_id_show #category_ticket_id').val(response.category_ticket_id).prop('disabled',true).removeAttr("name");
                $('#ticket_group_user_receiver_id_show #group_user_receiver_id').val(response.group_user_receiver_id).prop('disabled',true).removeAttr("name");
                $('#ticket_user_receiver_id_show #user_id').val(response.user_receiver_id).prop('disabled',true).removeAttr("name");
                $('#ticket_priority_ticket_id_show #priority_ticket_id').val(response.priority_ticket_id).prop('disabled',true).removeAttr("name");
                $('#ticket_status_ticket_id_show #status_ticket_id').val(response.status_ticket_id).prop('disabled',true).removeAttr("name");
                $("#div_comment_section").show();
                $('#div_modal_campos').show();
                getComentarios(response.id,response.user_owner_id);
                  
               
              },
              error: function(xhr, status, error) {
                  alert("error");
                  console.error(error);
              }
              
          })});
});
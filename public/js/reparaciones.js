$(document).ready(function(){

    $("#reparacion_id").focusout(function(){		
		exists($("#reparacion_id").val());
	});

    function exists(idReparacion){
        
        var route = '/findReparacionById/'+idReparacion;
        
        $('#reparacionMsg').empty();
        if($("#reparacion_id").val()=="")
            return;
       
        $.get( route , function(response, state){
            console.log(response);
            if ( response.length <= 0 ){
                $("#reparacion_id").val("");
                $('#reparacionMsg').append("Ingrese una Reparación válida");
            }
            else if (response.length > 0){
                $("#reparacion_id").val(response[0].id);
                $('#reparacionMsg').append("Reparación verificada correctamente");
                buscarClienteReparacion(response[0].id_cliente,1);
            }
                
        } );
    }
    function buscarClienteReparacion(idCliente,tipoTicket){ // buscar cliente segun el id ingresado
        if(tipoTicket==1){//reparacion
            var route = "/findClienteReparacionById/"+idCliente;
        }else{
            return;
        }
        var i=0; 
        
        $.get( route , function(response, state){
            
            console.log(response);
            $("#cliente_id").empty(); // al buscar, antes, limpio selects 
            
            if ( response.length <= 0 )
                $("#cliente_id").append("<option selected='selected' value=''>Cliente no encontrado</option>");
            if (response.length > 1)
                $("#cliente_id").append("<option selected='selected' value=''>Seleccione el cliente - </option>");		
            for(i=0; i<response.length; i++){
                $("#cliente_id").append("<option value='"+response[i].id+"'> "+response[i].nombreDniCuit+ "</option>" );
            }		
            
        } );
    };

})
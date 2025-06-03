$(document).ready(function(){

    $("#derivacion_id").focusout(function(){		
		exists($("#derivacion_id").val());
	});

    function exists(idDerivacion){
        
        var route = '/findDerivacionById/'+idDerivacion;
        
        $('#derivacionMsg').empty();
        if($("#derivacion_id").val()=="")
            return;
            
        $.get( route , function(response, state){
            console.log(response);
            if ( response.length <= 0 ){
                $("#derivacion_id").val("");
                $('#derivacionMsg').append("Ingrese una Derivación Válida");
            }
            else if (response.length > 0){
                $("#derivacion_id").val(response[0].id);
                $('#derivacionMsg').append("Derivación verificada correctamente");
                buscarClienteDerivacion(response[0].cliente_derivacion_id,2);
            }
                
        } );
    }
    
    function buscarClienteDerivacion(idCliente,tipoTicket){ // buscar cliente segun el id ingresado
        if(tipoTicket==2){//derivacion
            var route = "/findClienteDerivacionById/"+idCliente;
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
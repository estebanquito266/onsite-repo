$(document).ready(function(){

    var basePath = '/riparazione/public';
    
    //---------------------------------------------------------------------------//		
    $("#buscarCliente").click( function(){ // p/buscar cliente segun lo ingresado
        //recargo los clientes
        var textoBuscar = $("#textoBuscarCliente").val();
        var i=0; 
        var route = "/buscarClientes/"+textoBuscar;
        
        $.get( route , function(response, state){
            
            //console.log(response);
            $("#cliente_id").empty(); // al buscar, antes, limpio selects 
            
            if ( response.length <= 0 )
                $("#cliente_id").append("<option selected='selected' value=''>Cliente no encontrado</option>");
            if (response.length > 1)
                $("#cliente_id").append("<option selected='selected' value=''>Seleccione el cliente - </option>");		
            for(i=0; i<response.length; i++){
                $("#cliente_id").append("<option value='"+response[i].id+"'> "+response[i].nombreDniCuit+ "</option>" );
            }		
            
        } );
        
    });
    //---------------------------------------------------------------------------//	

    //---------------------------------------------------------------------------//		
    $("#buscarClienteDerivacion").click(function () {
        //recargo los clientes
        var textoBuscarCliente = $("#textoBuscarClienteDerivacion").val();

        var routeBuscar = "/buscarClientesDerivaciones/" + textoBuscarCliente;
        $.get(routeBuscar, function (response, state) {

            $("#cliente_id").empty();
            if (response.length <= 0)
                $("#cliente_id").append("<option selected='selected' value=''>Cliente Derivación no encontrado</option>");
            if (response.length > 1)
                $("#cliente_id").append("<option selected='selected' value=''>Seleccione el cliente derivación - </option>");
            for (i = 0; i < response.length; i++) {
                $("#cliente_id").append("<option value='" + response[i].id + "'> " + response[i].nombre + "</option>");
            }
        });

    });
    //---------------------------------------------------------------------------//	
        
    });
    
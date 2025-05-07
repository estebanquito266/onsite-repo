$(document).ready(function () {

    //-----------------------------------------------------------------------//		

   //REPARACIONES ONSITE
   $("#reparacionesOnsiteForm").validate({
       rules: {
           //clave: "required",
           id_empresa_onsite: "required",
           sucursal_onsite_id: "required",
           //id_terminal: "required",

           id_tipo_servicio: "required",
           id_estado: "required"
       },
       messages: {
           //clave: "Por favor, ingrese una clave",
           id_empresa_onsite: "Por favor, seleccione una empresa",
           sucursal_onsite_id: "Por favor, seleccione una sucursal",
           //id_terminal: "Por favor, seleccione una terminal",

           id_tipo_servicio: "Por favor, seleccione un tipo de servicio",
           id_estado: "Por favor, seleccione un estado",
       },
       errorElement: "em",
       errorPlacement: function (error, element) {
           // Add the `invalid-feedback` class to the error element
           error.addClass("invalid-feedback");
           if (element.prop("type") === "checkbox") {
               error.insertAfter(element.next("label"));
           } else {
               error.insertAfter(element);
           }
       },
       highlight: function (element, errorClass, validClass) {
           $(element).addClass("is-invalid").removeClass("is-valid");
       },
       unhighlight: function (element, errorClass, validClass) {
           $(element).addClass("is-valid").removeClass("is-invalid");
       }
   });
   //-----------------------------------------------------------------------//		

   $("#id_empresa_onsite").change(function () { // p/buscar
       limpiar();
       //getSucursales();
       validarEmpresaOnsite();

   });

   $("#refreshSucursal").click(function () { // p/buscar
       limpiar();
       // getSucursales();
   });

   //-----------------------------------------------------------------------//		

   $("#sucursal_onsite_id").change(function () { // p/buscar
       var tipo_terminal = $("#id_tipo_terminal").val();
       if (tipo_terminal == 1) {
           limpiarTerminal();
           getTerminales();
       }
       else {
           limpiarSistema();
           getSistemas();
       }

       var idSucursal = $('#sucursal_onsite_id').val();
       if (idSucursal) {
           $('#editSucursal').prop('disabled', false);
       }
       else {
           $('#editSucursal').prop('disabled', true);
           //$("#id_terminal_reparacion").empty();
       }

   });

   $("#refreshTerminal").click(function () { // p/buscar
       limpiarTerminal();
       getTerminales();
   });

   $("#refreshSistema").click(function () { // p/buscar
       limpiarSistema();
       getSistemas();
   });

  




   //---------------------------------------------------------------------------//

   $("#empresa_onsite_id").change(function () { // si cambia 
       limpiarSucursal();
       getSucursales();

   });
   //---------------------------------------------------------------------------//    


   


   








   //---------------------------------------------------------------------------//

   $("#id_terminal").change(function () { // si cambia 

       var idTerminal = $('#id_terminal').val();

       if (idTerminal) {
           $('#editTerminal').prop('disabled', false);
       }
       else {
           $('#editTerminal').prop('disabled', true);
           //$("#id_terminal_reparacion").empty();
       }

   });











   //beber ---------------------------------------------------------------------------//
   $("#id_empresa_onsite").change(function () {
       var id_empresa_onsite = $("#id_empresa_onsite").val();

       var route = "/obtenerTipoTerminal/" + id_empresa_onsite;

       $.get(route, function (response, state) {
           valores = Object.values(response);
           tipo_terminal = valores[0];
           company_id = valores[1];
           document.getElementById("id_tipo_terminal").value = tipo_terminal;
           document.getElementById("company_id").value = company_id;
           if (tipo_terminal == 1) {         //modificar a ==
               //obtenerTipos terminales y cargar el selector            
               $('#terminal').css('display', 'block');
               $('#sistema').css('display', 'none');
           }
           else {
               //obtener los sistemas onsite y cargar el selector
               $('#terminal').css('display', 'none');
               $('#sistema').css('display', 'block');
           }
       });
   });

   $("#id_tipo_servicio").change(function () {
       tipo_servicio = $('#id_tipo_servicio').val();
       if (tipo_servicio == 60) {
           $('#puestaMarcha').css('display', 'block');
           $('#companyActivo').css('display', 'none');
       }
       else {
           $('#companyActivo').css('display', 'block');
           $('#puestaMarcha').css('display', 'none');
       }

   });



   //------------- VALIDACIÓN DE FECHAS -----------------------------//
   $("#fecha_coordinada").on('change',function () { // si cambia 

       var fechaCoordinada = new Date($('#fecha_coordinada').val()).getTime();
       var fechaIngreso = new Date($('#fecha_ingreso').val()).getTime();
       if (fechaCoordinada < fechaIngreso) {
           showToast("La fecha coordinada no puede ser menor a la fecha de ingreso.", '', 'error');
       }

   }); 

});

//---------------------------------------------------------------------------//
//---------------------------------------------------------------------------//
function limpiar() {
   limpiarSucursal();
   limpiarTerminal();
}

function limpiarSucursal() {
   $("#sucursal_onsite_id").empty();
}

function limpiarTerminal() {
   $("#id_terminal").empty();
}

function limpiarSistema() {
   $("#sistema_onsite_id").empty();
}

function getSucursales() {

   var idEmpresaOnsite = $("#empresa_onsite_id").val();

   var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite;

   $.get(route, function (response, state) {

       limpiarSucursal(); // al buscar, antes, limpio selects y deshabilito botones

       if (response.length <= 0)
           $("#sucursal_onsite_id").append("<option selected='selected' value=''>Sucursal Onsite no encontrada</option>");

       if (response.length > 1)
           $("#sucursal_onsite_id").append("<option selected='selected' value=''>Seleccione la sucursal onsite - </option>");

       for (i = 0; i < response.length; i++) {
           $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] " + response[i].localidad + "</option>");
       }


   });
}

function getTerminales() {
   var idSucursalOnsite = $('#sucursal_onsite_id').val();
   var route = "/buscarTerminalesOnsite/" + idSucursalOnsite;

   $.get(route, function (response, state) {

       limpiarTerminal(); // al buscar, antes, limpio selects y deshabilito botones

       if (response.length <= 0)
           $("#id_terminal").append("<option selected='selected' value=''>Terminal Onsite no encontrada</option>");

       if (response.length > 1)
           $("#id_terminal").append("<option selected='selected' value=''>Seleccione la terminal onsite - </option>");

       for (i = 0; i < response.length; i++) {
           $("#id_terminal").append("<option value='" + response[i].nro + "'> " + response[i].nro + " - " + response[i].marca + " - " + response[i].modelo + " - " + response[i].serie + "</option>");
       }


   });

}

function getSistemas() {
   var idSucursalOnsite = $('#sucursal_onsite_id').val();
   var route = "/buscarSistemasOnsite/" + idSucursalOnsite;

   $.get(route, function (response, state) {

       limpiarSistema(); // al buscar, antes, limpio selects y deshabilito botones

       if (response.length <= 0)
           $("#sistema_onsite_id").append("<option selected='selected' value=''>Sistema Onsite no encontrado</option>");

       if (response.length > 1)
           $("#sistema_onsite_id").append("<option selected='selected' value=''>Seleccione el sistema onsite - </option>");

       for (i = 0; i < response.length; i++) {
           $("#sistema_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].id + " - " + response[i].nombre + "</option>");
       }


   });

}


//---------------------------------------------------------------------------//
function deshabilitar() {
   $("#sucursal_onsite_id").empty();
   $('#editarSucursal').prop('disabled', true);
}

//---------------------------------------------------------------------------//

function limpiarModalFormSucursal() {
   $('#datosSucursales')[0].reset();
   $('#mensaje-error-sucursal').html('');
   $('#mensaje-error-sucursal').css('display', 'none');
}

//---------------------------------------------------------------------------//

function limpiarModalFormTerminal() {
   $('#datosTerminales')[0].reset();
   $('#mensaje-error-terminal').html('');
   $('#mensaje-error-terminal').css('display', 'none');
}



//---------------------------------------------------------------------------//

function validarEmpresaOnsite() {

   var idEmpresaOnsite = $("#id_empresa_onsite").val();

   var route = "/getEmpresaOnsite/" + idEmpresaOnsite;

   $.get(route, function (response, state) {
       mostrarCamposTerminalSistema(response.tipo_terminales)
       if (response.generar_clave_reparacion == 1) {
           $('#clave').val('');
       }
       $('#clave').attr('readonly', (response.generar_clave_reparacion == 1));
   });
   /******************************************************************************** */
}

function mostrarCamposTerminalSistema(id_empresa_onsite) {
   tipo_servicio = $('#id_tipo_servicio').val();
   if (tipo_servicio == 60) {
       $('#puestaMarcha').css('display', 'block');
       $('#companyActivo').css('display', 'none');
   }
   else {
       $('#companyActivo').css('display', 'block');
       $('#puestaMarcha').css('display', 'none');
   }

}




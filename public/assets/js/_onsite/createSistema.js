$(function () {

  $('form input').on('keydown', (function (e) {
    if (e.keyCode == 13 || e.keyCode == 10) {
        e.preventDefault();
        return false;
    }
}));

  /* en edit trae unidades del sistema */
  /* sistemaid = $('#sistema_onsite_id').find(':selected').val();
  if (sistemaid > 0) {
  getUnidadesPorSistema(sistemaid);
  getComprador(sistemaid);} */


  /* en EDIT cheque si país seleccionado es Argentina */
  pais = $('#select_pais').val();
  /* setInputLocalidades(pais); */

  $('#empresa_instaladora_admins').on('change', function () {
    idEmpresa = $(this).find(':selected').val();
    getObrasPorEmpresa(idEmpresa);    

    idEmpresaInstaladora = $(this).find(':selected').val();
    nombreEmpresaInstaladora = $(this).find(':selected').text();
    email = $(this).find(':selected').data('email');
    nombre = $(this).find(':selected').data('nombre');
    telefono = $(this).find(':selected').data('telefono');

    $('#empresa_instaladora_id').val(idEmpresaInstaladora);
    $('#empresa_instaladora_nombre').val(nombreEmpresaInstaladora);
    $('#responsable_email').val(email);
    $('#responsable_nombre').val(nombre);
    $('#responsable_telefono').val(telefono);

    getEmpresaOnsiteAdmins();
  });


  cargandoBgh = makeLoader('Cargando datos de empresa y obras');
  $('#cargando_bgh').html(cargandoBgh);
  setTimeout(function () {
    $('#cargando_bgh').html('');
  }, 2500
  );

  setTimeout(function () {
    resetSmartWizard();
    $('.formulario_obra').removeAttr('hidden');
  }, 3000
  );

  $('#boton_enviar').on('click', function (e) {

    e.preventDefault();
    $("#modalConfirmacion").modal('toggle');
    loader = makeLoader('Procesando...');
    $('.bodymodalConfirmacion').html(loader);

    setTimeout(function () {
      $("#modalConfirmacion").modal('toggle');
      window.location.href = "/sistemaOnsite/";
    }, 2000);
  });

  /* UNIDADES EXTERNAS */
  $('.sistemas_creados').on('click', '.createUE', function (e) {
    e.preventDefault();
    idSistema = $(this).data('idsistema');
    idObra = $('#obra_onsite_id').val();
    nombreobra = $('#obra_nombre').val();
    nombreSistema = $(this).data('nombresistema');

    $("#sistema_onsite_id_unidades").append(
      "<option value="
      + idSistema
      + " data-idobra="
      + idObra
      + " data-nombre_sistema='"
      + nombreSistema
      + "'>"
      + nombreSistema
      + '<small> (Obra: '
      + nombreobra
      + ')</small>'
      + "</option>");

    $("#modalUE").modal('toggle');
    setTimeout(() => {

      $('#sistema_onsite_id_unidades').val(idSistema);
      $('#sistema_onsite_id_unidades').val(idSistema);
      $('#sistema_onsite_id_unidades').trigger('change');
      $('#sistema_onsite_id_unidades').prop('disabled', true);
    }, 500);


  });

  $('#guardarModalUE').on('click', function (e) {
    e.preventDefault();
    storeUnidadExterior();
  });

  $('#cerrarModalUE').on('click', function (e) {
    e.preventDefault();
    $("#modalUE").modal('toggle');

  });

  /* UNIDADES INTERNAS */

  $('.sistemas_creados').on('click', '.createUI', function (e) {
    e.preventDefault();
    idSistema = $(this).data('idsistema');
    idObra = $('#obra_onsite_id').val();
    nombreobra = $('#obra_nombre').val();
    nombreSistema = $(this).data('nombresistema');

    $("#sistema_onsite_id_unidades_interiores").append(
      "<option value="
      + idSistema
      + " data-idobra="
      + idObra
      + " data-nombre_sistema='"
      + nombreSistema
      + "'>"
      + nombreSistema
      + '<small> (Obra: '
      + nombreobra
      + ')</small>'
      + "</option>");

    $("#modalUI").modal('toggle');
    setTimeout(() => {

      $('#sistema_onsite_id_unidades_interiores').val(idSistema);
      $('#sistema_onsite_id_unidades_interiores').trigger('change');
      $('#sistema_onsite_id_unidades').prop('disabled', true);
    }, 500);


  });

  $('#guardarModalUI').on('click', function (e) {
    e.preventDefault();
    storeUnidadInterior();
  });

  $('#cerrarModalUI').on('click', function (e) {
    e.preventDefault();
    $("#modalUI").modal('toggle');

  });

  /* comprador */

  $('.sistemas_creados').on('click', '.createComprador', function (e) {
    e.preventDefault();
    idSistema = $(this).data('idsistema');
    $('#sistema_onsite_id_comprador').val(idSistema);

    idObra = $('#obra_onsite_id').val();
    nombreobra = $('#obra_nombre').val();
    nombreSistema = $(this).data('nombresistema');

    $("#CompradorModal").modal('toggle');

  });

  $('#guardarCompradorModal').on('click', function (e) {
    e.preventDefault();
    storeComprador();
  });

  $('#cerrarCompradorModal').on('click', function (e) {
    e.preventDefault();
    $("#CompradorModal").modal('toggle');
  });



  $('#select_pais').on('change', function () {

    pais = $(this).val();
    showLocalidades(pais);
  })


  $('#provincia').on('change', function () {
    idProvincia = $(this).val();
    getLocalidades(idProvincia);
  });

  $('#botonGuardar').on('click', function (e) {
    e.preventDefault();
    storeSistema();
  });

  $('#next-btn').on('click', function () {

    /* chequeo si es el último step del wizard form */
    segment = $(location).attr('href');
    largo = parseInt($(location).attr('href').length) - 1;


    obra_onsite_id = $('#obra_onsite_id').val();
    sistema_nombre = $('#sistema_nombre').val();
    empresa_onsite_id = $('#empresa_onsite_id').val();


    if (segment[largo] == 1) { 

      if(obra_onsite_id > 0) {
        $('#smartwizard').smartWizard("next");
      }
      else {
        showToast('Debe completar todos los datos', '', 'error');
      }

    };

    if (segment[largo] == 2) {

      if(sistema_nombre && empresa_onsite_id > 0) {
        storeSistema();
        $('#smartwizard').smartWizard("next");
        $('#next-btn').addClass('d-none');
      }
      else {
        if(!empresa_onsite_id || empresa_onsite_id == 0) {
          showToast('Debe seleccionar una empresa', '', 'error');
        }
        else {
          showToast('Debe completar todos los datos', '', 'error');
        }
      }

    };    
    
    /*
    if (segment[largo] == 3) {
      obra_onsite_id = $('#obra_onsite_id').val();

      getObraOnsiteWithSistema(obra_onsite_id);
      $('#smartwizard').smartWizard("next");
      
      
    };
    */


    /* if (crearnuevo == 1) {
      createObraForm();
    } */


  });

  $('#prev-btn').on('click', function () {
    $('#next-btn').show();
  });

  $('#reset-btn').on('click', function () {
    $('input[type=text]').val('');
    $('input[type=email]').val('');
    $('input[type=number]').val('');
    $('.multiselect-dropdown').val('');
    $('.multiselect-dropdown').trigger('change');
    $('#next-btn').prop('disabled', false);

  });

  $('.nav-item').on('click', function () {
    $('#next-btn').show();
  });

  $('#obra_onsite_id').on('change', function () {
    sistemaid = $(this).find(':selected').val();
    /* idObra = $(this).find(':selected').data('idobra'); */
    idObra = $(this).find(':selected').val();
    nombreSistema = $(this).find(':selected').data('nombre_sistema');
    /* $('#obra_onsite_id').val(idObra); */
    $('#obra_nombre').val(nombreSistema);
    var idEmpresaOnsite = $("#empresa_onsite_id").val();

    /* getObraOnsite(idObra); */
    limpiarSucursal();
    getSucursalesOnsite(idEmpresaOnsite);

  });


  $('#obra_onsite_id').on('change', function () {

    sistemaid = $(this).find(':selected').val();
    idObra = $(this).find(':selected').val();

    nombreSistema = $(this).find(':selected').text();
    /* $('#obra_onsite_id').val(idObra);
    $('#sistema_nombre').val(nombreSistema); */

    /* getUnidadesPorSistema(sistemaid); */
    /* getComprador(sistemaid); */
  });

  $('#apellido').on('change', function () {
    nombre = $('#primer_nombre').val();
    apellido = $('#apellido').val();
    $('#nombre').val(nombre + ', ' + apellido);
  }
  );


  $('#empresa_instaladora_id').on('change', function () {

    idEmpresa = $(this).find(':selected').val();
    getObrasPorEmpresa(idEmpresa);
  });


});


function getObrasPorEmpresa(idEmpresa) {
  var rutaModelos = "/getObrasPorEmpresa/" + idEmpresa;


  $.get(rutaModelos, function (response) {

    console.log(response);

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


function getUnidadesPorSistema(idSistema) {
  var rutaModelos = "/getUnidadesPorSistema/" + idSistema;

  return $.get(rutaModelos, function (response) {
    console.log(response);
    $('.unidades').html('');
    $('.unidades').append(
      '<tr><td>UNIDADES EXTERIORES</td></tr>'
      + '<tr>'
      + '<th>id</th>'
      + '<th>MODELO</th>'
      + '<th>SERIE</th>'
      + '<th>DIRECCION</th>'
      + '<th>FAJA GARANTÍA</th>'
      + '</tr>'
    );


    for (let index = 0; index < response.unidades_exteriores.length; index++) {
      $('.unidades').append(
        '<tr>'
        + '<td>'
        + '<a href="/unidadExterior/'
        + response.unidades_exteriores[index].id
        + '/edit" target="_blank">'
        + response.unidades_exteriores[index].id
        + '</a>'
        + '</td>'
        + '<td>'
        + response.unidades_exteriores[index].modelo
        + '</td>'
        + '<td>'
        + response.unidades_exteriores[index].serie
        + '</td>'
        + '<td>'
        + response.unidades_exteriores[index].direccion
        + '</td>'
        + '<td>'
        + response.unidades_exteriores[index].faja_garantia
        + '</td>'
        + '</tr>'
      );

    }

    $('.unidades').append(
      '<tr><td>UNIDADES INTERIORES</td></tr>'
      + '<tr>'
      + '<th>id</th>'
      + '<th>MODELO</th>'
      + '<th>SERIE</th>'
      + '<th>DIRECCION</th>'
      + '<th>FAJA GARANTÍA</th>'
      + '</tr>'
    );

    for (let index = 0; index < response.unidades_interiores.length; index++) {
      $('.unidades').append(
        '<tr>'
        + '<td>'
        + '<a href="/unidadInterior/'
        + response.unidades_interiores[index].id
        + '/edit" target="_blank">'
        + response.unidades_interiores[index].id
        + '</a>'
        + '</td>'
        + '<td>'
        + response.unidades_interiores[index].modelo
        + '</td>'
        + '<td>'
        + response.unidades_interiores[index].serie
        + '</td>'
        + '<td>'
        + response.unidades_interiores[index].direccion
        + '</td>'
        + '<td>'
        + response.unidades_interiores[index].faja_garantia
        + '</td>'
        + '</tr>'
      );

    }


  });
}

function getAllObras() {
  var rutaModelos = "/getObrasPorEmpresa/" + 1;

  return $.get(rutaModelos, function (response) {

  });


}

function getLocalidades(idProvincia) {

  $("#localidad").html('');

  var rutaModelos = "/getLocalidades/" + idProvincia;


  $.get(rutaModelos, function (response, state) {

    if (response.length <= 0)
      $("#localidad").append("<option selected='selected' value=''>Localidad</option>");

    if (response.length > 1)
      $("#localidad").append("<option selected='selected' value=''>Seleccione la localidad onsite - </option>");

    for (i = 0; i < response.length; i++) {
      $("#localidad").append("<option value='" + response[i].id + "'> " + response[i].localidad + "</option>");
    }



  });
}

function resetSmartWizard() {


  $('#smartwizard').smartWizard("reset");
  return true;

}

function validateForm() {

  obra = $('#obra_onsite_id').val();
  sistema = $('#obra_onsite_id').val();
  dni = $('#dni').val();


  if (obra > 0 && sistema > 0 && dni !== null) {

    $('.swal2-error').attr("hidden", true);
    $('.swal2-success').attr("hidden", false);
    $('#boton_enviar').removeClass('btn-warning');
    $('#boton_enviar').addClass('btn-success');
    $('#boton_enviar').attr("disabled", false);

    showResumen();
  }

  else {

    $('#boton_enviar').removeClass('btn-success');
    $('#boton_enviar').addClass('btn-warning');

    $('#boton_enviar').attr("disabled", true);
    $('.swal2-error').attr("hidden", false);
    $('.swal2-success').attr("hidden", true);

    $('.resumen_form').html(
      '<span>Revise errores de carga</span>'
    );
  }
}

function showResumen() {
  $('.resumen_form').html(
    '<span>'
    + 'Empresa Instaladora: '
    + $("#empresa_instaladora_id option:selected").text()
    + '<br>'

    + 'Obra: '
    + $("#obra_onsite_id option:selected").text()
    + '<br>'

    + 'Comprador: '
    + $("#nombre").val()
    + '<br>'

    + '</span>'
  );
}

function setInputLocalidades(pais) {

  if (pais !== 'Argentina') {
    //$('#provincia_div').addClass('hidden');
    $('#provincia_div').hide();
    $('#localidad_div').html(
      '<label>Localidad</label>'
      + '<input type="text" name="localidad" id="localidad" class="form-control mb-3">'
      + '</input> '
    );

  }

  else {
    $('#provincia_div').show();
    $('#localidad_div').html(
      '<label>Localidad</label>'
      + '<select name="localidad" id="localidad" class="form-control multiselect-dropdown  mb-3">'
      + '</select> '
    );


  }
}

function getCompradorPorSistema(idSistema) {

  return $.ajax({
    url: "/getCompradorPorSistema/" + idSistema,
    type: 'GET',
  }
  );
}

function getComprador(idSistema) {

  getCompradorPorSistema(idSistema).then(detalle => {

    $('#dni').val(detalle.dni);
    $('#primer_nombre').val(detalle.primer_nombre);
    $('#apellido').val(detalle.apellido);
    $('#select_pais').val(detalle.pais);
    $('#select_pais').trigger('change');

    $('#provincia').val(detalle.provincia_onsite_id);
    $('#provincia').trigger('change');

    setTimeout(() => {
      $('#localidad').val(detalle.localidad_onsite_id);
      $('#localidad').trigger('change');
    }, 1000);

    $('#domicilio').val(detalle.domicilio);
    $('#email').val(detalle.email);
    $('#celular').val(detalle.celular);
    $('#id_comprador').val(detalle.id);


  }).catch(error => {
    console.log('Error al procesar la petición. TRACE: ');
    console.log(error.responseJSON.trace);
    showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
  });

}

function updateComprador(idComprador) {

  updateCompradorPorId(idComprador).then(detalle => {


  }).catch(error => {
    console.log('Error al procesar la petición. TRACE: ');
    console.log(error.responseJSON.trace);
    showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
  });

}



function updateCompradorPorId(idComprador) {

  dataForm = setDataComprador();

  $.ajax({
    url: '/updateCompradorPorId/' + idComprador,
    type: 'POST',
    data: dataForm,
    contentType: false,
    processData: false,
    success: function (data) {
      showToast('Se ha actualizado comprador Onsite correctamente: ' + '[' + data.id + '] ' + data.nombre, '', 'success');
      $('#smartwizard').smartWizard("next");


    },
    fail: function (data) {
      showToast(data, '', 'error');
    },
    error: function (data) {
      errores = data.responseJSON.errors;
      for (var key in errores) {
        if (errores.hasOwnProperty(key)) {
          showToast(errores[key], '', 'error');
        }
      }
    }
  }
  );
}

function setDataComprador() {

  var dataForm = new FormData();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  dataForm.append('_token', CSRF_TOKEN);

  nombre = $('#primer_nombre').val();
  apellido = $('#apellido').val();

  dataForm.append('primer_nombre', nombre);
  dataForm.append('dni', $('#dni').val());

  dataForm.append('apellido', apellido);
  dataForm.append('nombre', nombre + ', ' + apellido);
  dataForm.append('pais', 'Argentina');
  dataForm.append('provincia_onsite_id', 26);
  dataForm.append('localidad_onsite_id', 1);
  dataForm.append('domicilio', '');
  dataForm.append('email', '');
  dataForm.append('celular', '');

  dataForm.append('sistema_onsite_id', $('#sistema_onsite_id_comprador').val());


  return dataForm;
}

function getEmpresaOnsiteAdmins() {

  idEmpresaInstaladora = $('#empresa_instaladora_id').val();
  getEmpresasOnsitePorInstaladoraId(idEmpresaInstaladora).then(detalle => {

    var size = Object.keys(detalle).length;

    if (size > 0) {
      $("#empresa_onsite_id").html('');

      for (let key in detalle) {

        $("#empresa_onsite_id").append("<option value='" + detalle[key].id + "'> " + detalle[key].nombre + "</option>");
      }
      $("#empresa_onsite_id").prop('disabled', true);
    }

    else {

      storeEmpresaOnsite(true);
    };

  }).catch(error => {
    console.log('Error al procesar la petición. TRACE: ');
    console.log(error.responseJSON.trace);
    showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
  });
}

function getEmpresasOnsitePorInstaladoraId(idEmpresaInstaladora) {

  return $.ajax({
    url: "/getEmpresasOnsitePorInstaladoraId/" + idEmpresaInstaladora,
    type: 'GET',
  }
  );
}

/* UNIDADES EXTERIORES */
function storeUnidadExterior() {

  dataForm = setDataUnidadExterior();

  $.ajax({
    url: '/storeUnidadExterior',
    type: 'POST',
    data: dataForm,
    contentType: false,
    processData: false,
    success: function (data) {
      showToast('Unidad Exterior creada correctamente: ' + '[' + data.id + '] ' + data.clave, '', 'success');
      $("#modalUE").modal('toggle');
      $('.form_ue').val('');
      getUnidadesExterioresPorSistema(data.sistema_onsite_id);
    },
    fail: function (data) {
      showToast(data, '', 'error');
    },
    error: function (data) {
      errores = data.responseJSON.errors;
      for (var key in errores) {
        if (errores.hasOwnProperty(key)) {
          showToast(errores[key], '', 'error');
        }
      }
    }
  }
  );
}

function setDataUnidadExterior() {

  anclaje_piso = 0;
  contra_sifon = 0;
  mm_500_ultima_derivacion_curva = 0;
  if ($('#anclaje_piso').is(":checked")) anclaje_piso = 1;
  if ($('#contra_sifon').is(":checked")) contra_sifon = 1;
  if ($('#mm_500_ultima_derivacion_curva').is(":checked")) mm_500_ultima_derivacion_curva = 1;


  var dataForm = new FormData();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  dataForm.append('_token', CSRF_TOKEN);

  dataForm.append('clave', 1);
  dataForm.append('empresa_onsite_id', $('#empresa_onsite_id').val());
  dataForm.append('modelo', $('#modelo').val());
  dataForm.append('direccion', $('#direccion').val());
  dataForm.append('faja_garantia', $('#faja_garantia').val());
  dataForm.append('serie', $('#serie').val());
  dataForm.append('sistema_onsite_id', $('#sistema_onsite_id_unidades').val());
  dataForm.append('medida_figura_1_a', $('#medida_figura_1_a').val());
  dataForm.append('medida_figura_1_b', $('#medida_figura_1_b').val());
  dataForm.append('medida_figura_1_c', $('#medida_figura_1_c').val());
  dataForm.append('medida_figura_1_d', $('#medida_figura_1_d').val());
  dataForm.append('medida_figura_2_a', $('#medida_figura_2_a').val());
  dataForm.append('medida_figura_2_b', $('#medida_figura_2_b').val());
  dataForm.append('medida_figura_2_c', $('#medida_figura_2_c').val());

  dataForm.append('anclaje_piso', anclaje_piso);
  dataForm.append('contra_sifon', contra_sifon);
  dataForm.append('mm_500_ultima_derivacion_curva', mm_500_ultima_derivacion_curva);

  dataForm.append('observaciones', $('#observaciones').val());

  return dataForm;
}

function getUnidadesExterioresPorSistema(idSistema) {

  $("#unidades_exteriores_creadas").html('');
  $('#unidades_exteriores_creadas').append();

  $.ajax({
    url: '/getUnidadesExterioresPorSistema/' + idSistema,
    type: 'GET',
    success: function (data) {
      showToast('Se listan las unidades exteriores del Sistema: ' + idSistema, '', 'success');
      console.log(data);
      tabla_unidades_exteriores = '';

      for (let index = 0; index < data.length; index++) {
        console.log(data[index]);
        if (index == 0) primer_fila = '<table class="table table-striped">'
          + '<tr> </tr><th>id</th><th>Sistema</th><th>Clave</th></tr>';
        else primer_fila = '';

        tabla_unidades_exteriores +=
          primer_fila
          + '<tr><td>'
          + data[index].id
          + '</td>'
          + '<td>'
          + data[index].sistema_onsite.nombre
          + '</td>'
          + '<td>'
          + data[index].clave
          + '</td>'
          + '</tr>'
      };

      $('#unidades_exteriores_creadas').append(tabla_unidades_exteriores);

      $('#unidades_exteriores_creadas').append('</table>');

    },
    fail: function (data) {
      showToast(data, '', 'error');
    },
    error: function (data) {
      errores = data.responseJSON.errors;
      for (var key in errores) {
        if (errores.hasOwnProperty(key)) {
          showToast(errores[key], '', 'error');
        }
      }
    }

  });
}

/* UNIDADES INTERIORES */
function storeUnidadInterior() {

  dataForm = setDataUnidadInterior();

  $.ajax({
    url: '/storeUnidadInterior',
    type: 'POST',
    data: dataForm,
    contentType: false,
    processData: false,
    success: function (data) {
      showToast('Unidad Interior creada correctamente: ' + '[' + data.id + '] ' + data.clave, '', 'success');
      $("#modalUI").modal('toggle');
      $('.form_ui').val('');
      getUnidadesInterioresPorSistema(data.sistema_onsite_id);
    },
    fail: function (data) {
      showToast(data, '', 'error');
    },
    error: function (data) {
      errores = data.responseJSON.errors;
      for (var key in errores) {
        if (errores.hasOwnProperty(key)) {
          showToast(errores[key], '', 'error');
        }
      }
    }
  }
  );
}

function setDataUnidadInterior() {

  var dataForm = new FormData();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  dataForm.append('_token', CSRF_TOKEN);

  dataForm.append('clave', 1);
  dataForm.append('empresa_onsite_id', $('#empresa_onsite_id').val());
  dataForm.append('modelo', $('#modelo_ui').val());
  dataForm.append('direccion', $('#direccion_ui').val());
  dataForm.append('faja_garantia', $('#faja_garantia_ui').val());
  dataForm.append('serie', $('#serie_ui').val());
  dataForm.append('sistema_onsite_id', $('#sistema_onsite_id_unidades_interiores').val());

  dataForm.append('observaciones', $('#observaciones_ui').val());

  return dataForm;
}

/* empresa onsite */

function getEmpresasOnsitePorInstaladora() {

  return $.ajax({
    url: "/getEmpresasOnsitePorInstaladora/",
    type: 'GET',
  }
  );
}

function getEmpresasOnsitePorInstaladoraId(idEmpresaInstaladora) {

  return $.ajax({
    url: "/getEmpresasOnsitePorInstaladoraId/" + idEmpresaInstaladora,
    type: 'GET',
  }
  );
}

function storeEmpresaOnsite(esAdmin) {

  dataForm = setDataEmpresaOnsite();

  $.ajax({
    url: '/storeEmpresaOnsite',
    type: 'POST',
    data: dataForm,
    contentType: false,
    processData: false,
    success: function (data) {
      showToast('Se ha creado Empresa Onsite correctamente: ' + '[' + data.id + '] ' + data.clave, '', 'success');
      if (esAdmin)
        getEmpresaOnsiteAdmins();
      else
        getEmpresaOnsite();
    },
    fail: function (data) {
      showToast(data, '', 'error');
    },
    error: function (data) {
      errores = data.responseJSON.errors;
      for (var key in errores) {
        if (errores.hasOwnProperty(key)) {
          showToast(errores[key], '', 'error');
        }
      }
    }
  }
  );
}

function setDataEmpresaOnsite() {

  var dataForm = new FormData();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  dataForm.append('_token', CSRF_TOKEN);

  clave = $('#empresa_instaladora_id').val() + $('#empresa_instaladora_nombre').val();

  dataForm.append('clave', clave);
  dataForm.append('nombre', clave);
  dataForm.append('pais', 'Argentina');
  dataForm.append('provincia_onsite_id', 26);
  dataForm.append('localidad_onsite_id', 1);
  dataForm.append('email_responsable', $('#responsable_email').val());
  dataForm.append('requiere_tipo_conexion_local', 1);
  dataForm.append('generar_clave_reparacion', 1);
  dataForm.append('empresa_instaladora_id', $('#empresa_instaladora_id').val());


  return dataForm;
}

function getEmpresaOnsite() {
  getEmpresasOnsitePorInstaladora().then(detalle => {

    var size = Object.keys(detalle).length;

    if (size > 0) {
      for (let key in detalle) {
        console.log(detalle[key]);
        $("#empresa_onsite_id").append("<option value='" + detalle[key].id + "'> " + detalle[key].nombre + "</option>");
      }
      $("#empresa_onsite_id").prop('disabled', true);
    }

    else {
      console.log('preocede a crear,,');
      storeEmpresaOnsite(false);
    };

  }).catch(error => {
    console.log('Error al procesar la petición. TRACE: ');
    console.log(error.responseJSON.trace);
    showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
  });
}



/* COMPRADOR ONSITE */

function storeComprador() {

  dataForm = setDataComprador();

  $.ajax({
    url: '/storeComprador',
    type: 'POST',
    data: dataForm,
    contentType: false,
    processData: false,
    success: function (data) {
      showToast('Comprador creado correctamente: ' + '[' + data.id + '] ' + data.nombre, '', 'success');
      $("#CompradorModal").modal('toggle');
      $('.form_comprador').val('');

    },
    fail: function (data) {
      showToast(data, '', 'error');
    },
    error: function (data) {
      errores = data.responseJSON.errors;
      for (var key in errores) {
        if (errores.hasOwnProperty(key)) {
          showToast(errores[key], '', 'error');
        }
      }
    }
  }
  );
}



function resetSmartWizard() {


  $('#smartwizard').smartWizard("reset");
  return true;

}



function getLocalidades(idProvincia) {

  $("#localidad").html('');

  var rutaModelos = "/getLocalidades/" + idProvincia;


  $.get(rutaModelos, function (response, state) {

    if (response.length <= 0)
      $("#localidad").append("<option selected='selected' value=''>Localidad</option>");

    if (response.length > 1)
      $("#localidad").append("<option selected='selected' value=''>Seleccione la localidad onsite - </option>");

    for (i = 0; i < response.length; i++) {
      $("#localidad").append("<option value='" + response[i].id + "'> " + response[i].localidad + "</option>");
    }



  });
}

function validateForm() {
  console.log('validando obra denver');
  obra = $('#nombre').val();

  if (obra.length > 0) {

    $('.swal2-error').attr("hidden", true);
    $('.swal2-success').attr("hidden", false);
    $('#boton_enviar').removeClass('btn-warning');
    $('#boton_enviar').addClass('btn-success');
    $('#boton_enviar').attr("disabled", false);

    showResumen();
  }

  else {

    $('#boton_enviar').removeClass('btn-success');
    $('#boton_enviar').addClass('btn-warning');

    $('#boton_enviar').attr("disabled", true);
    $('.swal2-error').attr("hidden", false);
    $('.swal2-success').attr("hidden", true);

    $('.resumen_form').html(
      '<span>Revise errores de carga</span>'
    );
  }
}

function showResumen() {
  $('.resumen_form').html(
    '<span>'
    + 'Empresa Instaladora: '
    + $("#empresa_instaladora_nombre").val()
    + '<br>'


    + 'Obra: '
    + $("#nombre").val()
    + '<br>'

    + '</span>'
  );
}

function showLocalidades(pais) {
  if (pais == 'Argentina') {
    $('#provincia_div').removeAttr('hidden');
    $('.localidad_div').html(
      '<label>Localidad</label>'
      + '<select name="localidad" id="localidad" class="form-control multiselect-dropdown  mb-3">'
      + '</select> '
    );

  }

  else {
    $('#provincia_div').prop('hidden', 'hidden');
    $('.localidad_div').html(
      '<label>Localidad</label>'
      + '<input type="text" name="localidad" id="localidad" class="form-control  mb-3">'
      + '</input> '
    );

  }
}


function getSucursalesOnsite(idEmpresaOnsite) {

  var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite;

  $.get(route, function (response, state) {
    console.log(response);
    limpiarSucursal(); // al buscar, antes, limpio selects y deshabilito botones

    if (response.length <= 0)
      $("#sucursal_onsite_id").append("<option selected='selected' value=1>DEFAULT</option>");

    if (response.length > 0) {

      for (i = 0; i < response.length; i++) {
        $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] " + "</option>");
      }

    }
    $("#sucursal_onsite_id").prop('disabled', true);
  });
}
function limpiarSucursal() {
  $("#sucursal_onsite_id").empty();
}

function showToast(mensaje, contexto, tipo) {
  toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut",
    "onclick": true
  }


  //toastr["success"]("Repuesto agregado correctamente. Click para ir al carrito", "REPUESTO ONSITE");
  toastr[tipo](mensaje); //se quita el titulo

}

function storeSistema() {

  empresa_onsite_id = $('#empresa_onsite_id').val();
  sucursal_onsite_id = $('#sucursal_onsite_id').val();
  obra_onsite_id = $('#obra_onsite_id').val();
  sistema_nombre = $('#sistema_nombre').val();
  comentarios = $('#comentarios').val();
  fecha_compra = $('#fecha_compra').val();
  numero_factura = $('#numero_factura').val();



  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  $.ajax({
    url: '/storeSistema',
    type: 'POST',
    data: {
      _token: CSRF_TOKEN,
      empresa_onsite_id: empresa_onsite_id,
      sucursal_onsite_id: sucursal_onsite_id,
      obra_onsite_id: obra_onsite_id,
      nombre: sistema_nombre,
      comentarios: comentarios,
      fecha_compra: fecha_compra,
      numero_factura: numero_factura
    },
    dataType: 'JSON',
    success: function (data) {
      showToast('Sistema creado correctamente: ' + data.id, '', 'success');
      $('#next-btn').prop('disabled', false);
      $('#comentarios').val('');
      $('#sistema_nombre').val('');

      getSistemasPorObra(obra_onsite_id);

    },
    fail: function (data) {
      showToast(data, '', 'error');
    },
    error: function (data) {
      errores = data.responseJSON.errors;
      for (var key in errores) {
        if (errores.hasOwnProperty(key)) {
          showToast(errores[key], '', 'error');
        }
      }
    }
  }
  );
}

function getSistemasPorObra(idObra) {

  $("#sistemas_creados").html('');
  $('#sistemas_creados').append();

  $.ajax({
      url: '/getSistemasPorObra/' + idObra,
      type: 'GET',
      success: function (data) {
          showToast('Se listan los sistemas de la Obra: ' + idObra, '', 'success');
          $('#sistemas_unidades_creados').prop('hidden', false);

          tabla_sistemas = '';

          for (let index = 0; index < data.length; index++) {

              if (index == 0) primer_fila = '<table class="table table-striped">'
                  + '<tr> </tr><th>id</th><th>nombre</th><th>UI</th><th>UE</th> </tr>';
              else primer_fila = '';
              tabla_sistemas +=
                  primer_fila
                  + '<tr><td>'
                  + data[index].id
                  + '</td>'
                  + '<td>'
                  + data[index].nombre
                  + '</td>'
                  + '<td>'
                  /* + '<a target="_blank"  class="btn btn-success" href="insertUISistema/' */
                  + '<button id="createUI" class="createUI btn btn-success" data-idsistema='
                  + data[index].id
                  + ' data-nombresistema="'
                  + data[index].nombre
                  + '">'
                  + 'Agregar UI</button>'
                  + '</td>'
                  + '<td>'
                  /* + '<a target="_blank" class="btn btn-success" href="insertUESistema/' */
                  + '<button id="createUE" class="createUE btn btn-success" data-idsistema='
                  + data[index].id

                  + ' data-nombresistema="'
                  + data[index].nombre
                  + '">'
                  + 'Agregar UE</button>'
                  + '</td>'

                  + '<td>'
                  + '<button id="createComprador" class="createComprador btn btn-success" data-idsistema='
                  + data[index].id
                  + ' data-nombresistema="'
                  + data[index].nombre
                  + '">'
                  + 'Crear Comprador</button>'
                  + '</td>'

                  + '</tr>'
          };

          $('#sistemas_creados').append(tabla_sistemas);

          $('#sistemas_creados').append('</table>');


      },
      fail: function (data) {
          showToast(data, '', 'error');
      },
      error: function (data) {
          errores = data.responseJSON.errors;
          for (var key in errores) {
              if (errores.hasOwnProperty(key)) {
                  showToast(errores[key], '', 'error');
              }
          }
      }

  });
}

function getUnidadesInterioresPorSistema(idSistema) {

  $("#unidades_interiores_creadas").html('');
  $("#unidades_interiores_creadas").append('');


  $.ajax({
      url: '/getUnidadesInterioresPorSistema/' + idSistema,
      type: 'GET',
      success: function (data) {
          showToast('Se listan las unidades interiores del Sistema: ' + idSistema, '', 'success');

          tabla_unidades_interiores = '';

          for (let index = 0; index < data.length; index++) {

              if (index == 0) primer_fila = '<table class="table table-striped">'
                  + '<tr> </tr><th>id</th><th>Sistema</th><th>Clave</th></tr>';
              else primer_fila = '';

              tabla_unidades_interiores +=
                  primer_fila
                  + '<tr><td>'
                  + data[index].id
                  + '</td>'
                  + '<td>'
                  + data[index].sistema_onsite.nombre
                  + '</td>'
                  + '<td>'
                  + data[index].clave
                  + '</td>'
                  + '</tr>'
          };

          $('#unidades_interiores_creadas').append(tabla_unidades_interiores);

          $('#unidades_interiores_creadas').append('</table>');

      },
      fail: function (data) {
          showToast(data, '', 'error');
      },
      error: function (data) {
          errores = data.responseJSON.errors;
          for (var key in errores) {
              if (errores.hasOwnProperty(key)) {
                  showToast(errores[key], '', 'error');
              }
          }
      }

  });
}
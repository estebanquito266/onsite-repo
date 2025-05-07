$(function () {
  /*
  document.querySelector("#dni").addEventListener("keypress", function (evt) {
    if (evt.which < 48 || evt.which > 57)
    {
        evt.preventDefault();
    }
  });
  */
  /* en edit trae unidades del sistema */
  sistemaid = $('#sistema_onsite_id').find(':selected').val();
  if (sistemaid > 0) {
    getUnidadesPorSistema(sistemaid);
    getComprador(sistemaid);
  }


  /* en EDIT cheque si país seleccionado es Argentina */
  pais = $('#select_pais').val();
  /* setInputLocalidades(pais); */

  nombreSistema = '';
  nombreEmpresa = '';

  $('#garantiaonsite').submit(function (e) {
    /* e.preventDefault(); */

  });

  $('#store_obra').on('click', function (e) {
    e.preventDefault();
    storeObra();
  });


  $('#save1').on('click', function (e) {

    idObra = $('#sistema_onsite_id').find(':selected').data('idobra');

    nombreSistema = $('#sistema_onsite_id').find(':selected').data('nombre_sistema');
    nombreEmpresa = $("#empresa_instaladora_id option:selected").text();

    if (!idObra) {
      showToast('Debe seleccionar un sistema', '', 'warning');
      return;
    }

    $('#obra_onsite_id').val(idObra);
    $('#sistema_nombre').val(nombreSistema);


    $('#smartwizard').smartWizard("next");
  });

  $('#save2').on('click', function (e) {

    dni = $('#dni').val();
    nombre = $('#primer_nombre').val();
    apellido = $('#apellido').val();
    localidad = $('#localidad').val();
    domicilio = $('#domicilio').val();
    celular = $('#celular').val();
    $('#nombre').val(nombre + ', ' + apellido);


    if (!nombre || !apellido || !localidad || !domicilio || !celular || !dni) {
      showToast('Debe completar todos los campos', '', 'warning');
      return;
    }

    idComprador = $('#id_comprador').val();

    updateComprador(idComprador);
  });

  $('#save3').on('click', function (e) {

    tipo = $('#tipo').val();

    if (!tipo) {
      showToast('Debe completar todos los campos', '', 'warning');
      return;
    }

    if (validateFormGarantia()) {
      $('#smartwizard').smartWizard("next");
    }
  });

  /*
  $('#next-btn').on('click', function () {
    idObra = $('#sistema_onsite_id').find(':selected').data('idobra');

    nombreSistema = $('#sistema_onsite_id').find(':selected').data('nombre_sistema');

    $('#obra_onsite_id').val(idObra);
    $('#sistema_nombre').val(nombreSistema);

    nombre = $('#primer_nombre').val();
    apellido = $('#apellido').val();
    $('#nombre').val(nombre + ', ' + apellido);

    // chequeo si es el último step del wizard form
    segment = $(location).attr('href');
    largo = parseInt($(location).attr('href').length) - 1;

    if (segment[largo] == 3) {
      // $(this).hide();
      validateFormGarantia();
    };

    if (segment[largo] == 2) {

      setTimeout(() => {
        $('#next-btn').show();
      }, 1000);
      //$('#next-btn').css('style', 'display: yes');

      idComprador = $('#id_comprador').val();
      updateComprador(idComprador);
    };

    if (segment[largo] == 1) {
      setTimeout(() => {
        $('#next-btn').show();
      }, 300);
      $('#next-btn').show();
    };

  });
  */

  $('#prev-btn').on('click', function () {

    $('#next-btn').show();

  });

  $('.nav-item').on('click', function () {

    $('#next-btn').show();

  });



  cargandoBgh = makeLoader('Cargando datos de empresa y obras');
  $('#cargando_bgh').html(cargandoBgh);
  setTimeout(function () {
    $('#cargando_bgh').html('');
  }, 2500
  );

  setTimeout(function () {
    resetSmartWizard();

    if ($('.formulario_obra').length) {
      $('.formulario_obra').removeAttr('hidden');
    }
  }, 3000
  );

  $('#select_pais').on('change', function () {
    pais = $(this).val();
    /* setInputLocalidades(pais); */
  });


  $('#provincia').on('change', function () {
    idProvincia = $(this).val();
    getLocalidades(idProvincia);
  });


  $('#refreshsistemasbutton').on('click', function (e) {
    e.preventDefault();
    sistemaid = $('#sistema_onsite_id').find(':selected').val();
    getUnidadesPorSistema(sistemaid);

  })


  $('#sistema_onsite_id').on('change', function () {

    sistemaid = $(this).find(':selected').val();
    idObra = $(this).find(':selected').data('idobra');
    idReparacion = $(this).find(':selected').data('idreparacion');

    nombreSistema = $(this).find(':selected').data('nombre_sistema');
    console.log('data idobra: ' + idObra + '--' + sistemaid);
    $('#obra_onsite_id').val(idObra);
    $('#sistema_nombre').val(nombreSistema);


    getUnidadesPorSistema(sistemaid);
    getComprador(sistemaid);
    getReparacion(idReparacion);



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

  /*
  $('#boton_enviar').on('click', function (e) {

    console.log('garantias - boton_enviar');

    e.preventDefault();
    $("#modalConfirmacion").modal('toggle');
    loader = makeLoader('Procesando...');
    $('.bodymodalConfirmacion').html(loader);

    setTimeout(function () {
      console.log('garantias - boton_enviar - setTimeout');

      $("#modalConfirmacion").modal('toggle');
      window.location.href = "/garantiaonsite/";
    }, 2000);
  });
  */

  $('#boton_enviar').on('click', function (e) {
    e.preventDefault();
    idTemplate = 6;

    getTemplate(idTemplate).then(detalle => {
        $("#modalConfirmacion").modal('toggle');
        $('.bodymodalConfirmacion').html('');
        $('.bodymodalConfirmacion').html(detalle);

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });
  });

  $('#aceptarmodalConfirmacion').on('click', function () {
    loader = makeLoader('Procesando...');
    $('.bodymodalConfirmacion').html('');
    $('.bodymodalConfirmacion').html(loader);
    $('#aceptarmodalConfirmacion').hide();
    $('#cerrarmodalConfirmacion').hide();

    $('#sistema_onsite_id option:selected').each(function () {
        idSistema = $(this).val();
        nombreSistema = $(this).text();
        idObra = $(this).data('idobra');

        storeGarantia(idSistema);
    });

    setTimeout(function () {
        $("#modalConfirmacion").modal('toggle');
        window.location.href = "/garantiaonsite/";
    }, 2500);

  });

  $('#cerrarmodalConfirmacion').on('click', function () {
      $("#modalConfirmacion").modal('toggle');
  });


  // BOTON ELIMINAR GARANTIA
  $('.eliminar_garantia_btn').on('click', function (event) {
      event.preventDefault();
      let id = $(this).data('garantia_id');
      let nombre = $(this).data('garantia_nombre');
      $('#garantia-delete-link').val('delGarantia/'+id);
      $('#modalConfirmacionTitle').html('Eliminar garantía');
      $('.subtitleModalConfirmacion').html(nombre);
      $('.bodymodalConfirmacion').html('¿Desea eliminar esta garantía?');        
      $("#modalConfirmacion").modal('toggle');
  });

  $('#aceptarmodalConfirmacion').on('click', function () {
      window.location.href = $('#garantia-delete-link').val();
  });

  $('#cerrarmodalConfirmacion').on('click', function () {
      $("#modalConfirmacion").modal('toggle');
  }); 


});


function getObrasPorEmpresa(idEmpresa) {
  var rutaModelos = "/getObrasPorEmpresa/" + idEmpresa;

  $.get(rutaModelos, function (response) {
    $("#sistema_onsite_id").html('');

    if (response.length <= 0)
      $("#sistema_onsite_id").append("<option selected='selected' value=''>Modelos no encontradas</option>");
    else {
      $("#sistema_onsite_id").append("<option selected='selected' value=''>Seleccione el modelo - </option>");

      for (i = 0; i < response.length; i++) {
        $("#sistema_onsite_id").append("<optgroup label='Obra: " + response[i].nombre + "' >");

        for (j = 0; j < response[i].sistema_onsite.length; j++) {

          if (response[i].sistema_onsite.length > 0) {

            if (response[i].sistema_onsite[j].reparacion_onsite.length > 0)
              idReparacion = response[i].sistema_onsite[j].reparacion_onsite[0].id;
            else idReparacion = 0;

            $("#sistema_onsite_id").append(
              "<option value="
              + response[i].sistema_onsite[j].id
              + " data-idobra="
              + response[i].sistema_onsite[j].obra_onsite_id
              + " data-nombre_sistema='"
              + response[i].sistema_onsite[j].nombre
              + "' data-idreparacion='"
              + idReparacion
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
      + '<th>VOID</th>'
      + '</tr>'
    );


    for (let index = 0; index < response.unidades_exteriores.length; index++) {

      etiquetas = '';
      if (response.unidades_exteriores[index].etiqueta.length > 0) {
        for (let j = 0; j < response.unidades_exteriores[index].etiqueta.length; j++) {
          etiquetas +=
            '<span class="badge badge-primary">'
            + response.unidades_exteriores[index].etiqueta[j].nombre
            + '</span>'
            + '<br>'
        };
      }

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
        + etiquetas
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
      + '<th>VOID</th>'
      + '</tr>'
    );

    for (let index = 0; index < response.unidades_interiores.length; index++) {
      etiquetas = '';
      if (response.unidades_interiores[index].etiqueta.length > 0) {
        for (let j = 0; j < response.unidades_interiores[index].etiqueta.length; j++) {
          etiquetas +=
            '<span class="badge badge-primary">'
            + response.unidades_interiores[index].etiqueta[j].nombre
            + '</span>'
            + '<br>'
        };
      }

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
        + etiquetas
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
  if ($('#smartwizard').length) {
    $('#smartwizard').smartWizard("reset");
    return true;
  }
  return false;

}

function validateFormGarantia() {

  obra = $('#obra_onsite_id').val();
  sistema = $('#sistema_onsite_id').val();
  dni = $('#dni').val();

  if (obra > 0 && sistema > 0 && dni !== null) {
    $('.swal2-error').attr("hidden", true);
    $('.swal2-success').attr("hidden", false);
    $('#boton_enviar').removeClass('btn-warning');
    $('#boton_enviar').addClass('btn-success');
    $('#boton_enviar').attr("disabled", false);

    showResumenGarantia();
    return true;
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

    showToast('ERROR. Revise Sistema Seleccionado, datos comprador y TIPO de Garantía', '', 'error');
    return false;
  }
}

function showResumenGarantia() {
  $('.resumen_form').html(
    '<span>'
    + 'Empresa Instaladora: '
    + nombreEmpresa
    + '<br>'

    + 'Sistema: '
    + $("#sistema_onsite_id option:selected").text()
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

  updateCompradorPorId(idComprador);
  /*
  updateCompradorPorId(idComprador).then(detalle => {


  }).catch(error => {
    console.log('Error al procesar la petición. TRACE: ');
    console.log(error.responseJSON.trace);
    showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
  });
  */
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
  dni = $('#dni').val();


  dataForm.append('dni', dni);

  dataForm.append('primer_nombre', nombre);
  dataForm.append('apellido', apellido);
  dataForm.append('nombre', nombre + ', ' + apellido);
  dataForm.append('pais', $('#select_pais').val());
  dataForm.append('provincia_onsite_id', $('#provincia').val());
  dataForm.append('localidad_onsite_id', $('#localidad').val());
  dataForm.append('domicilio', $('#domicilio').val());
  dataForm.append('email', $('#email').val());
  dataForm.append('celular', $('#celular').val());

  return dataForm;
}

function getReparacionPorId(idReparacion) {

  return $.ajax({
    url: "/getReparacionPorId/" + idReparacion,
    type: 'GET',
  }
  );
}

function getReparacion(idReparacion) {
  console.log('getReparacion');
  console.log('idReparacion -- ' + idReparacion);

  getReparacionPorId(idReparacion).then(detalle => {
    console.log(detalle);
    if (detalle.aclaracion_cliente != null) {
      destinatario_informe = detalle.aclaracion_cliente;
    }
    else {
      destinatario_informe = 'No especificado.';
    }

    idTipoGarantia = 1;
    if (detalle) {
      if (detalle.reparacion_checklist_onsite) {
        if (detalle.reparacion_checklist_onsite[0]) {
          idTipoGarantia = detalle.reparacion_checklist_onsite[0].puesta_marcha_satisfactoria;
        }
      }
    }

    $('#tipo').val(idTipoGarantia);
    $('#observaciones').val(detalle.informe_tecnico);

    $('#informe_observaciones').val(detalle.id);
    $('#destinatario_informe').val(destinatario_informe);

  }).catch(error => {
    console.log('Error al procesar la petición. TRACE: ');
    console.log(error.responseJSON.trace);
    showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
  });
}

function storeGarantia(idSistema) {
  dataForm = setDataStoreGarantia(idSistema);

  $.ajax({
      url: '/storeGarantia',
      type: 'POST',
      data: dataForm,
      contentType: false,
      processData: false,
      success: function (data) {
          //$("#modalConfirmacion").modal('toggle');
          showToast('Registro creado correctamente: ' + data.id, '', 'success');
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

function setDataStoreGarantia(idSistema) {
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
  idEmpresa = $('#empresa_instaladora_id').val();
  idUser = $('#user_id').val();
  obra_onsite_id = $('#obra_onsite_id').val();
  sistema_nombre = $('#sistema_nombre').val();
  dni = $('#dni').val();
  id_comprador = $('#id_comprador').val();
  primer_nombre = $('#primer_nombre').val();
  apellido = $('#apellido').val();
  nombre = $('#nombre').val();
  domicilio = $('#domicilio').val();
  email = $('#email').val();
  celular = $('#celular').val();
  fecha_compra = $('#fecha_compra').val();
  informe_observaciones = $('#informe_observaciones').val();
  destinatario_informe = $('#destinatario_informe').val();
  observaciones = $('#observaciones' ).val();
  sistema_onsite_id = $('#sistema_onsite_id').val();
  select_pais = $('#select_pais').val();
  provincia_onsite_id = $('#provincia').val();
  localidad = $('#localidad').val();  
  tipo = $('#tipo').val();

  /* ************************ */

  var dataForm = new FormData();
  dataForm.append('_token', CSRF_TOKEN);
  dataForm.append('empresa_instaladora_id', idEmpresa);
  dataForm.append('user_id', idUser);
  dataForm.append('obra_onsite_id', obra_onsite_id);
  dataForm.append('sistema_nombre', sistema_nombre);
  dataForm.append('dni', dni);
  dataForm.append('id_comprador', id_comprador);
  dataForm.append('primer_nombre', primer_nombre);
  dataForm.append('apellido', apellido);
  dataForm.append('nombre', nombre);
  dataForm.append('domicilio', domicilio);
  dataForm.append('email', email);
  dataForm.append('celular', celular);
  dataForm.append('fecha_compra', fecha_compra);
  dataForm.append('fecha', fecha_compra);
  dataForm.append('informe_observaciones', informe_observaciones);
  dataForm.append('destinatario_informe', destinatario_informe);

  /* dataForm.append('obra_onsite_id', idObra); */
  dataForm.append('sistema_onsite_id', idSistema);
  dataForm.append('pais', select_pais);
  dataForm.append('provincia_onsite_id', provincia_onsite_id);
  dataForm.append('localidad', localidad);
  dataForm.append('garantia_tipo_onsite_id', tipo);

  return dataForm;
}
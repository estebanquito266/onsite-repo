$(function () {

  $('form input').on('keydown', (function (e) {
    if (e.keyCode == 13 || e.keyCode == 10) {
      e.preventDefault();
      return false;
    }
  }));


  $('#empresa_instaladora_admins').on('change', function () {
    idEmpresa = $(this).find(':selected').val();
    getObrasPorEmpresa(idEmpresa);
    console.log(idEmpresa);


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

  /* chequeo si es el último step del wizard form */
  segment = $(location).attr('href');
  largo = parseInt($(location).attr('href').length) - 1;
  console.log(segment[largo]);

  if (segment[largo] == 1) {
    $('#next-btn').hide();
    $('#prev-btn').hide();

  };

  /* -------- */

  $('#next-btn').on('click', function () {

    /* chequeo si es el último step del wizard form */
    segment = $(location).attr('href');
    largo = parseInt($(location).attr('href').length) - 1;

    if (segment[largo] == 4) {

    };

    $('#smartwizard').smartWizard("next");
  });

  $('#prev-btn').on('click', function () {
    $('#next-btn').show();
  });

  $('#reset-btn').on('click', function () {


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
    console.log('cnsuutlado sismtes.as..');
    getSistemasPorObra(idObra);
    getAllBouchersPorObra(idObra).then(resultado => {
      console.log(resultado);
      makeDataBouchers(resultado);
    }).catch(error => {
      showToast('error revise', '', 'error');
      console.log(error.responseJSON.trace);
    });

    /* getObraOnsite(idObra); */
    /* limpiarSucursal();
    getSucursalesOnsite(idEmpresaOnsite);
 */

  });


  $('#tableSistemas').on('click', '.sistema_button', function () {



    idSistema = $(this).data('id');
    getSistemaPorId(idSistema).then(resultado => {


      setTimeout(() => {
        makeDataUnidadesInteriores(resultado.unidades_interiores);
      }, 100);

      setTimeout(() => {
        makeDataUnidadesExteriores(resultado.unidades_exteriores);
      }, 1000);

      setTimeout(() => {
        makeDataUnidadesReparaciones(resultado.reparacion_onsite);
      }, 2000);

      $('#smartwizard').smartWizard("next");

      $('#next-btn').show();
      $('#prev-btn').show();

    }).catch(error => {
      console.log(error.responseJSON.trace);
    });

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
      console.log(data);
      $('#sistemas_unidades_creados').prop('hidden', false);

      tabla_sistemas = '';

      let dataset = [];

      for (let index = 0; index < data.length; index++) {

        button = makeButton('button', 'sistema_button', data[index].id, data[index].nombre, 'btn btn-success sistema_button', 'Ver');

        if (data[index].comentarios != 'null') {
          comentarios = data[index].comentarios;
        }

        else comentarios = '-';

        dataset.push([
          data[index].id,
          data[index].nombre,
          data[index].fecha_compra,
          data[index].numero_factura,
          comentarios,
          button

        ]);

      };
      console.log(dataset);

      columns =
        [
          { title: "id" },
          { title: "nombre" },
          { title: "fecha_compra" },
          { title: "numero_factura" },
          { title: "comentarios" },
          { title: "Detalle" },

        ]
        ;

      destino = '#tableSistemas';

      completeDataSistemas(dataset, columns, destino);


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


function completeDataSistemas(dataSet, columns, destino) {


  if ($.fn.dataTable.isDataTable(destino)) {
    $(destino).DataTable().destroy();
  }

  var table = $(destino).DataTable({
    data: dataSet,
    columns: columns,
    dom: 'Bfrtip',
    "order": [[0, "asc"]]

  });
  /* table.column(1).visible(false);
  table.column(5).visible(false);
  table.column(6).visible(false); */


  /* $('.add_button').prop("disabled", true); */


}

function makeButton(type, id, dataId, datanombre, buttonclass, text) {
  button = "<button type=" + type + " id=" + id + " class='" + buttonclass
    + "' data-id=" + dataId
    + " data-part_name=" + '"' + datanombre + '"'
    + ">" + text + "</button>";

  return button;

}



function makeDataUnidadesInteriores(data) {
  let dataset = [];

  for (let index = 0; index < data.length; index++) {
    dataset.push([
      data[index].id,
      data[index].clave,

    ]);
  };
  console.log(dataset);

  columns =
    [
      { title: "id - Unidades Interiores" },
      { title: "clave" },

    ];

  destino = '#unidades_interiores';

  completeDataSistemas(dataset, columns, destino);

}

function makeDataUnidadesExteriores(data) {
  let dataset = [];

  for (let index = 0; index < data.length; index++) {
    dataset.push([
      data[index].id,
      data[index].clave,

    ]);
  };
  console.log(dataset);

  columns =
    [
      { title: "id - Unidades Exteriores" },
      { title: "clave" },

    ];

  destino = '#unidades_exteriores';

  completeDataSistemas(dataset, columns, destino);

}

function makeDataUnidadesReparaciones(data) {
  let dataset = [];

  for (let index = 0; index < data.length; index++) {

    if (data[index].id_tecnico_asignado != 14) {
      dataset.push([
        '<a target="_blank" href="/comprobanteVisita/' + data[index].id + '">' + data[index].id + '</a>',
        data[index].clave,
        data[index].tarea,
        data[index].fecha_ingreso,
        data[index].fecha_coordinada,
        data[index].informe_tecnico,
        data[index].id_tecnico_asignado,
        data[index].tecnico_asignado.name,

      ]);
    }

  };
  console.log(dataset);

  columns =
    [
      { title: "id - Visitas" },
      { title: "clave" },
      { title: "tarea" },
      { title: "fecha_ingreso" },
      { title: "fecha Coordinada" },
      { title: "informe_tecnico" },
      { title: "id_tecnico_asignado" },
      { title: "Técnico" },


    ];

  destino = '#reparaciones';

  completeDataSistemas(dataset, columns, destino);

}

function makeDataBouchers(data) {
  let dataset = [];

  for (let index = 0; index < data.length; index++) {
    if (data[index].sistema_consumido)
      sistema_consumido = data[index].sistema_consumido.nombre;
    else
      sistema_consumido = '-';

    if (data[index].consumido == 0) consumido = 'NO'
    else consumido = 'SI';

    if (data[index].solicitud_id > 0)
      solicitud = '<a href="solicitudesOnsite/' + data[index].solicitud_id + '">' + data[index].solicitud_id + '</a>';
    else solicitud = '';


    dataset.push([
      data[index].id,
      data[index].tipo_boucher.nombre,
      data[index].obra_onsite.nombre,
      solicitud,
      data[index].codigo,
      '$' + parseFloat(data[index].precio).toFixed(2),
      consumido,
      sistema_consumido,
    ]);
  };


  columns =
    [
      { title: "id - Boucher" },
      { title: "Tipo" },
      { title: "Obra" },
      { title: "idSolicitud" },
      { title: "codigo" },
      { title: "precio" },
      { title: "consumido" },
      { title: "Sistema" },


    ];

  destino = '#table_bouchers';

  completeDataSistemas(dataset, columns, destino);

}


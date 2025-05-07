/* COMPRADOR ONSITE */

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


/* sistemas */

function storeSistema() {

  dataForm = setDataSistema();

  $.ajax({
    url: '/storeSistema',
    type: 'POST',
    data: dataForm,
    contentType: false,
    processData: false,
   
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


function setDataSistema() {

  var dataForm = new FormData();
  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  var empresa_onsite_id = $('#empresa_onsite_id').val();
  var sucursal_onsite_id = $('#sucursal_onsite_id').val();
  var obra_onsite_id = $('#obra_onsite_id').val();
  var sistema_nombre = $('#sistema_nombre').val();
  var comentarios = $('#comentarios').val();
  var fecha_compra = $('#fecha_compra').val();
  var numero_factura = $('#numero_factura').val();


  dataForm.append('_token', CSRF_TOKEN);

  dataForm.append('empresa_onsite_id', empresa_onsite_id);
  dataForm.append('sucursal_onsite_id', sucursal_onsite_id);
  dataForm.append('obra_onsite_id', obra_onsite_id);
  dataForm.append('nombre', sistema_nombre);
  dataForm.append('comentarios', comentarios);
  dataForm.append('fecha_compra', fecha_compra);
  dataForm.append('numero_factura', numero_factura);

  return dataForm;
}


/* obras */


function storeObra() {

  dataForm = setDataStoreObra();

  $.ajax({
      url: '/storeObra',
      type: 'POST',
      data: dataForm,
      contentType: false,
      processData: false,
      success: function (data) {
          showToast('Obra creada correctamente: ' + data.id, '', 'success');
          $('#obra_onsite_id').val(data.id);
          $('#obra_nombre').val(data.nombre);
          idEmpresaOnsite = data.empresa_onsite_id;


          $('#store_obra').prop('disabled', true);
          blockDivByClass('empresa_obra', 300000);
          blockDivByClass('empresa_obra1', 300000);
          $('#smartwizard').smartWizard("next");
          
          getSucursales(idEmpresaOnsite);
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

function setDataStoreObra() {

  empresa_instaladora_id = $('#empresa_instaladora_id').val();
  empresa_onsite_id = $('#empresa_onsite_id').val();
  responsable_email = $('#responsable_email').val();
  responsable_nombre = $('#responsable_nombre').val();
  responsable_telefono = $('#responsable_telefono').val();
  empresa_instaladora_nombre = $('#empresa_instaladora_nombre').val();
  nombre = $('#nombre').val();
  nro_cliente_bgh_ecosmart = $('#nro_cliente_bgh_ecosmart').val();
  select_pais = $('#select_pais').val();
  provincia = $('#provincia').val();
  localidad = $('#localidad').val();
  domicilio = $('#autocomplete').val();
  longitud = $('#longitude').val();
  latitud = $('#latitude').val();

  cantidad_unidades_exteriores = $('#cantidad_unidades_exteriores').val();
  cantidad_unidades_interiores = $('#cantidad_unidades_interiores').val();
  estado = $('#estado').val();
  estado_detalle = $('#estado_detalle').val();

  
  var ins = document.getElementById('esquema_archivo').files.length;


  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  /* ************************ */

  var dataForm = new FormData();

  for (var x = 0; x < ins; x++) {
      dataForm.append("esquema_archivo[]", document.getElementById('esquema_archivo').files[x]);
  }

  dataForm.append('_token', CSRF_TOKEN);
  dataForm.append('empresa_instaladora_id', empresa_instaladora_id);
  dataForm.append('empresa_onsite_id', empresa_onsite_id);
  dataForm.append('responsable_email', responsable_email);
  dataForm.append('nombre', nombre);
  dataForm.append('nro_cliente_bgh_ecosmart', nro_cliente_bgh_ecosmart);
  dataForm.append('pais', select_pais);
  dataForm.append('provincia_onsite_id', provincia);
  dataForm.append('localidad_onsite_id', localidad);
  dataForm.append('domicilio', domicilio);
  dataForm.append('longitud', longitud);
  dataForm.append('latitud', latitud);
  dataForm.append('cantidad_unidades_exteriores', cantidad_unidades_exteriores);
  dataForm.append('cantidad_unidades_interiores', cantidad_unidades_interiores);
  dataForm.append('empresa_instaladora_nombre', empresa_instaladora_nombre);
  dataForm.append('empresa_instaladora_responsable', responsable_nombre);
  dataForm.append('responsable_telefono', responsable_telefono);
  dataForm.append('estado', estado);
  dataForm.append('estado_detalle', estado_detalle);
  dataForm.append('localidad_texto', localidad);
  dataForm.append('clave', '1234claveprovisoria');


  return dataForm;
}

/* checklist */

function storeCheckList() {
  setCheckList();

  if ($('#vip').is(":checked")) vip = 1;
  if ($('#requiere_zapatos_seguridad').is(":checked")) requiere_zapatos_seguridad = 1;
  if ($('#requiere_casco_seguridad').is(":checked")) requiere_casco_seguridad = 1;
  if ($('#requiere_proteccion_visual').is(":checked")) requiere_proteccion_visual = 1;
  if ($('#requiere_proteccion_auditiva').is(":checked")) requiere_proteccion_auditiva = 1;
  if ($('#requiere_art').is(":checked")) requiere_art = 1;
  if ($('#clausula_no_arrepentimiento').is(":checked")) clausula_no_arrepentimiento = 1;


  cuit = $('#cuit').val();
  razon_social = $('#razon_social').val();
  cnr_detalle = $('#cnr_detalle').val();
  obra_onsite_id = $('#obra_onsite_id').val();

  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  $.ajax({
      url: '/storeCheckList',
      type: 'POST',
      data: {
          _token: CSRF_TOKEN,
          requiere_zapatos_seguridad: requiere_zapatos_seguridad,
          requiere_casco_seguridad: requiere_casco_seguridad,
          requiere_proteccion_visual: requiere_proteccion_visual,
          requiere_art: requiere_art,
          requiere_proteccion_auditiva: requiere_proteccion_auditiva,
          clausula_no_arrepentimiento: clausula_no_arrepentimiento,
          cuit: cuit,
          razon_social: razon_social,
          cnr_detalle: cnr_detalle,
          company_id: 2,
          obra_onsite_id: obra_onsite_id,
          vip: vip

      },
      dataType: 'JSON',
      success: function (data) {
          showToast('Check list creado correctamente: ' + data.id, '', 'success');

          $('#store_checklist').prop('disabled', true);
          blockDivByClass('checklist', 300000);
          blockDivByClass('div_art', 300000);

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

function setCheckList() {
  requiere_zapatos_seguridad = 0;
  requiere_casco_seguridad = 0;
  requiere_proteccion_visual = 0;
  requiere_proteccion_auditiva = 0;
  requiere_art = 0;
  clausula_no_arrepentimiento = 0;
  vip = 0;
}
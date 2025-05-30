async function storeReparacionesMirgor() {

  dataForm = setDataStoreReparacionesMirgor();

  showToast('Iniciando proceso.', '', 'success');
  /*
  la siguiente function compara el último id registrado con el nuevo id para calcular cantidad
  de registros ya procesados
  se ejecuta con timeout para que se hayan registrado filas para informar. De lo contrario
  encuentra cero filas procesadas y termina la ejecución */

  let ultimoid = await getLastIdProcessed();
  let shouldContinue = true;

  setTimeout(() => {
      console.log('shouldContinue',shouldContinue);
      if (shouldContinue) {
          let calculateProcessed = getRowsProcessedFunction(ultimoid, 0);
      }
  }, 2000);
 

  blockDivByClass('formulario_obra', 0);

  $.ajax({
    url: '/importarReparacionOnsite',
    type: 'POST',
    data: dataForm,
    contentType: false,
    processData: false,

    success: function (data) {
      

        setTimeout(() => {
          showToast('Fin del proceso de importación.', '', 'success');
          unblockByClass('formulario_obra');
          $('.bodymodalConfirmacion').html('Cantidad de filas procesadas correctamente: ' + data.rows_processed);
          $('.bodymodalConfirmacion').append('<br><br>');
          if (data.rows_failures.length > 1) {
            $('.bodymodalConfirmacion').append('Cantidad de filas no procesadas: ' + data.rows_failures_total + '.');
            $('.bodymodalConfirmacion').append('<br>');
            $('.bodymodalConfirmacion').append('Revise la/s fila/s Nº: ' + data.rows_failures);
          }

          else
            $('.bodymodalConfirmacion').append('No se encontraron errores ni duplicaciones en las filas importadas.');

          $('#modalConfirmacion').modal('toggle');
        }, 3000);

    },

    fail: function (data) {
      showToast(data, '', 'error');
      unblockByClass('formulario_obra');

      unblockByClass('formulario_obra');
      $('.bodymodalConfirmacion').html(data);
      $('#modalConfirmacion').modal('toggle');


      console.log(data);
    },

    error: function (data) {
      console.log(data);
      //showToast('No es posible procesar la transacción. Revise la consola.', '', 'error');
      unblockByClass('formulario_obra');
      $('#modalConfirmacion').modal('toggle');

      processErrors(data);
    },
    complete:function(){
      console.log('Is complete');
      shouldContinue = false;
    }
  }
  );
}

function setDataStoreReparacionesMirgor() {

  /* empresa_instaladora_id = $('#empresa_instaladora_id').val();
   */

  var ins = document.getElementById('file').files.length;


  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

  /* ************************ */

  var dataForm = new FormData();

  for (var x = 0; x < ins; x++) {
    dataForm.append("file", document.getElementById('file').files[x]);
  }

  dataForm.append('_token', CSRF_TOKEN);

  /* dataForm.append('empresa_instaladora_id', empresa_instaladora_id);
  */

  return dataForm;
}




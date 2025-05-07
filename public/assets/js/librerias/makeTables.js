
function completeDataTables(dataSet, columns, destino) {
  console.log(destino);

  if ($.fn.dataTable.isDataTable('#' + destino)) {
    $('#' + destino).DataTable().destroy(true);
    //TO-do poner dinamico el id de la table
    $('.table-responsive').html('<table id="' + destino + '" class="table table-striped table-bordered" cellspacing="0" width="100%"></table>');
  }

  var table = $('.table-responsive > ' + '#' + destino).DataTable({
    data: dataSet,
    columns: columns,
    dom: 'Bfrtip' 
  });
  /* table.column(1).visible(false);
  table.column(5).visible(false);
  table.column(6).visible(false); */


  /* $('.add_button').prop("disabled", true); */


}
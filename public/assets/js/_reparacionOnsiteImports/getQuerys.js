function getRowsProcessed() {

  return $.ajax({
    url: "/getRowsReparacionesProcessed",
    type: 'GET',
  }
  );
}

function getReparacionesRecepcionar() {

  return $.ajax({
    url: "/getReparacionesRecepcionar/",
    type: 'GET',
  }
  );
}





/* empresa onsite */




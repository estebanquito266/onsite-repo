function getCompradorPorSistema(idSistema) {

  return $.ajax({
    url: "/getCompradorPorSistema/" + idSistema,
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

function getSistemaPorId(idSistema) {
  return $.ajax({
    url: '/getSistemaPorId/' + idSistema,
    type: 'GET'
  });
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




function getSucursalesOnsite(idEmpresaOnsite) {

  return $.ajax({
    url: "/buscarSucursalesOnsite/" + idEmpresaOnsite,
    type: 'GET',
  }
  );
}


function getUnidadesExteriores(idSistema) {

  return $.ajax({
    url: '/getUnidadesExterioresPorSistema/' + idSistema,
    type: 'GET',
  }
  );
}

function getUnidadesInteriores(idSistema) {

  return $.ajax({
    url: '/getUnidadesInterioresPorSistema/' + idSistema,
    type: 'GET',
  }
  );
}


function getLocalidadesPorId(idProvincia) {

  return $.ajax({
    url: "/getLocalidades/" + idProvincia,
    type: 'GET',
  }
  );
}

function getSistemasPorObraQuery(idObra) {

  return $.ajax({
    url: '/getSistemasPorObra/' + idObra,
        type: 'GET',

  }
  );
}


function getEmpresasOnsitePorInstaladora() {

  return $.ajax({
      url: "/getEmpresasOnsitePorInstaladora/",
      type: 'GET',
  }
  );
}

function getObraOnsiteWithSistemaPorObra(idObra) {

  return $.ajax({
      url: "/getObraOnsiteWithSistema/" + idObra,
      type: 'GET',
  }
  );
}














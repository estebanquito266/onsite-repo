function makeTextArea(idElemento) {
    textArea =
        '<div class="col-12 col-md-4 mt-3">'
        + '<textarea name="'
        + idElemento
        + '"'
        + 'class="'
        + idElemento
        + ' form-control col-12'
        + '" cols="10" rows="1"></textarea></div>';

    return textArea
}

function makeLabel(idSistema, nombre_sistema) {
    sistemaSeleccionado =
        '<div class="col-12 col-md-4 mt-3">'
        + '[' + idSistema + '] - ' + nombreSistema
        + '</div>';

    return sistemaSeleccionado;

}

function makeInput(inputClass, id, value) {
    input = "<input readonly type='number' setp='0.01' min='0' class='" + inputClass + "' id='" + id
        + "' value='"
        + value
        + "'></input>";

    return input;
}
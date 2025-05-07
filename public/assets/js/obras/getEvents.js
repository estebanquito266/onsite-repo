$(function () {

    /* evita envío de formulario con tecla enter */
    $('form input').on('keydown', (function (e) {
        if (e.keyCode == 13 || e.keyCode == 10) {
            e.preventDefault();
            return false;
        }
    }));

    /* arranque y reseteo previo del formulario con loader */
    inicioFormularioSteps();

    /* carga de esquemas (imagenes o archivos) */
    $(document).on('change', '.file-input', function () {

        var filesCount = $(this)[0].files.length;
        var textbox = $(this).prev();
        if (filesCount === 1) {
            var fileName = $(this).val().split('\\').pop();
            textbox.text(fileName);
        } else {
            textbox.text(filesCount + ' files selected');
        }
    });

    /* provincia y localidad segun el pais */
    pais = $('#select_pais').val();
    showLocalidades(pais);

    $('#select_pais').on('change', function () {
        pais = $(this).val();
        showLocalidades(pais);
    })

    $('#provincia').on('change', function () {
        idProvincia = $(this).val();
        getLocalidades(idProvincia);
    });

    /* empresa instaladora */

    idEmpresaInstaladora = $('#empresa_instaladora_id').val();
    if (idEmpresaInstaladora > 0) getEmpresaOnsite();

    $('#empresa_onsite_id').on('change', function () {
        id_empresa_onsite = $(this).val();
        getSucursales(id_empresa_onsite);
    });

    $('#empresa_instaladora_admins').on('change', function () {
        idEmpresaInstaladora = $(this).find(':selected').val();
        nombreEmpresaInstaladora = $(this).find(':selected').text();
        email = $(this).find(':selected').data('email');
        nombre = $(this).find(':selected').data('nombre');
        telefono = $(this).find(':selected').data('telefono');
        numero = $(this).find(':selected').data('numero');

        $('#empresa_instaladora_id').val(idEmpresaInstaladora);
        $('#empresa_instaladora_nombre').val(nombreEmpresaInstaladora);
        $('#responsable_email').val(email);
        $('#responsable_nombre').val(nombre);
        $('#responsable_telefono').val(telefono);

        if (numero > 0)
            $('#nro_cliente_bgh_ecosmart').val(numero);

        else
            $('#nro_cliente_bgh_ecosmart').val(99);

        getEmpresaOnsiteAdmins();
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


    /* control ART  y CNR sistemas */
    $('#sistema_onsite_id').on('change', function () {
        sistemaid = $(this).find(':selected').val();
        idObra = $(this).find(':selected').data('idobra');
        nombreSistema = $(this).find(':selected').data('nombre_sistema');
        $('#obra_onsite_id').val(idObra);
        $('#obra_nombre').val(nombreSistema);
        var idEmpresaOnsite = $("#empresa_onsite_id").val();

        /* getObraOnsite(idObra); */
        limpiarSucursal();
        getSucursalesOnsite(idEmpresaOnsite);

    });

    $('#requiere_art').on('change', function () {
        check = $('#requiere_art').prop('checked');
        if (check == true)
            $('#div_art').prop('hidden', false);

        else
            $('#div_art').prop('hidden', true);
        $('#clausula_no_arrepentimiento').attr('checked', false);

    });

    $('#clausula_no_arrepentimiento').on('change', function () {
        check = $('#clausula_no_arrepentimiento').prop('checked');
        if (check == true)
            $('.div_cnr').prop('hidden', false);
        else
            $('.div_cnr').prop('hidden', true);
    });


    /* obra, checklist, sistemas y finalizar */
    $('#store_obra').on('click', function (e) {
        e.preventDefault();
        storeObra();
    });

    $('#store_checklist').on('click', function (e) {
        e.preventDefault();
        storeCheckList();
    });

    $('#botonGuardarSistema').on('click', function (e) {
        e.preventDefault();
        storeSistema();
    });

    $('#finalizar_carga').on('click', function (e) {
        e.preventDefault();
        $(this).prop('disabled', true);

        obra_onsite_id = $('#obra_onsite_id').val();
        blockDivByClass('sistemas', 300000);
        blockDivByClass('sistemas_unidades_creados', 300000);
        getObraOnsiteWithSistema(obra_onsite_id);
        $('#smartwizard').smartWizard("next");
    });

    $('#boton_enviar').on('click', function (e) {

        e.preventDefault();
        $("#modalConfirmacion").modal('toggle');
        loader = makeLoader('Procesando...');
        $('.bodymodalConfirmacion').html(loader);

        setTimeout(function () {
            $("#modalConfirmacion").modal('toggle');
            window.location.href = "/obrasOnsite/";
        }, 2000);
    });


});















// Forms Multi Select

$( document ).ready(function() {

    setTimeout(function () {

        $(".multiselect-dropdown").select2({
            theme: "bootstrap4",
            placeholder: "Seleccione",
        });

        $('#example-single').multiselect({
            inheritClass: true
        });

        $('#example-multi').multiselect({
            inheritClass: true
        });

        $('#example-multi-check').multiselect({
            inheritClass: true
        });

    }, 2000);

});
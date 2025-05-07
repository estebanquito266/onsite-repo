function showToast(mensaje, contexto, tipo) {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "onclick": true
    }


    //toastr["success"]("Repuesto agregado correctamente. Click para ir al carrito", "REPUESTO ONSITE");
    toastr[tipo](mensaje); //se quita el titulo

}

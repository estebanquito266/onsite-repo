$(function()
{
    
})

function makeLoader(mensaje) {

    let loader = '<div class="font-icon-wrapper float-left mr-3 mb-3 col-12">'
        + '<div class="loader-wrapper d-flex justify-content-center align-items-center">'
        + '<div class="loader">'
        + '<div class="ball-spin-fade-loader">'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '<div></div>'
        + '</div>'
        + '</div>'
        + '</div>'
        + '<p>'
        + mensaje
        + '</p>'
        + '</div>';

    return loader;
}

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
    if (contexto == 'carrito') {
        toastr.options.onclick = function () {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#carrito_repuestos").offset().top
            }, 500);
        }
    }

    //toastr["success"]("Repuesto agregado correctamente. Click para ir al carrito", "REPUESTO ONSITE");
    toastr[tipo](mensaje); //se quita el titulo

}

function blockDivByClass(clase, timeout) {
    console.log(clase);
    $("." + clase).block({
        message: $(                
            '<div class="loader mx-auto">\n' +
            
            '  <div class="ball-grid-pulse">\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '      <div class="bg-white"></div>\n' +
            '  </div>\n' +
            '</div>'),
            
        timeout: timeout
    });
}
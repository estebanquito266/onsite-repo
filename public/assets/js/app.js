var wrapper = document.getElementById("signature-pad"),
    clearButton = wrapper.querySelector("[data-action=clear]"),
    saveButton = wrapper.querySelector("[data-action=save]"),
    canvas = wrapper.querySelector("canvas"),
    signaturePad;

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}
/*
function mostrarContenedor(id){	
	var contenedor = document.getElementById(id);
	if(contenedor.style.display == 'none')
		contenedor.style.display = '';
	else 
		contenedor.style.display = 'none';
}
*/

window.onresize = resizeCanvas;
resizeCanvas();

signaturePad = new SignaturePad(canvas);
//var firma1 = document.getElementsByName("firma1");
var firma1 = document.getElementById("firma1");
var firma2 = document.getElementById("firma2");
var firma3 = document.getElementById("firma3");
var firma4 = document.getElementById("firma4");
var mjefirma = document.getElementById("mjefirma");

clearButton.addEventListener("click", function (event) {
    signaturePad.clear();
});

saveButton.addEventListener("click", function (event) {
    if (signaturePad.isEmpty()) {
        alert("Please provide signature first.");
    } else {
        //window.open(signaturePad.toDataURL());
		//signaturePad.toDataURL("image/jpeg");
		firma1.value = signaturePad.toDataURL();
		firma2.value = signaturePad.toDataURL();
		firma3.value = signaturePad.toDataURL();
		firma4.value = signaturePad.toDataURL();
		mjefirma.innerHTML = 'Firma generada correctamente';
    }
});

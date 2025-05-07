var wrapper1 = document.getElementById("signature-pad-1"),
    clearButtonFirmaCliente = wrapper1.querySelector("[name=clearCliente]"),
    saveButtonFirmaCliente = wrapper1.querySelector("[name=saveCliente]"),
    canvas1 = wrapper1.querySelector("canvas"),
    signaturePad1;

	//canvas.height = 350;
	
// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.
function resizeCanvas1() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
	
	/*alert('ratio '+ratio+
	'\n - window.devicePixelRatio '+window.devicePixelRatio+
	'\n - canvas.width'+canvas.width+
	'\n - canvas.height'+canvas.height+
	'\n - canvas.offsetWidth '+canvas.offsetWidth+
	'\n - canvas.offsetHeight '+canvas.offsetHeight+
	'\n - canvas.offsetWidth * ratio '+(canvas.offsetWidth * ratio) + 
	'\n - canvas.offsetHeight * ratio '+(canvas.offsetHeight * ratio) +
	'\n - clientWidth '+canvas.clientWidth+' - clientHeight  '+canvas.clientHeight );*/
	
	//alert(canvas1.width + ' - ' + canvas1.offsetWidth + ' - ' + canvas1.height + ' - ' + canvas1.offsetHeight + ' - ' + ratio );
    canvas1.width = canvas1.width * ratio; //canvas1.offsetWidth ;//* ratio;
	
    canvas1.height = canvas1.height * (ratio * 2.5); //canvas1.offsetHeight ;//* ratio;
	
	/*alert('ratio '+ratio+
	' - canvas.width'+canvas.width+
	' - canvas.height'+canvas.height+
	' - canvas.offsetWidth '+canvas.offsetWidth+
	' - canvas.offsetHeight '+canvas.offsetHeight);*/
	
    canvas1.getContext("2d").scale(ratio, ratio);
	
	
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

window.onresize = resizeCanvas1;
resizeCanvas1();

signaturePad1 = new SignaturePad(canvas1);
//var firma1 = document.getElementsByName("firma1");




clearButtonFirmaCliente.addEventListener("click", function (event) {
    signaturePad1.clear();
});

saveButtonFirmaCliente.addEventListener("click", function (event) {
	var firma, mjeFirma, advertenciaFirma = null;


		firma = document.getElementById("firma_cliente");
		mjeFirma = document.getElementById("mjeFirmaCliente");				
		advertenciaFirma = document.getElementById("advertenciaFirmaCliente");				


    if (signaturePad1.isEmpty()) {
        alert("Debe ingresar la firma!");
    } else {
        //window.open(signaturePad.toDataURL());
		//signaturePad.toDataURL("image/jpeg");
		firma.value = signaturePad1.toDataURL();		
		
		mjeFirma.innerHTML = 'Firma del cliente generada correctamente';
		advertenciaFirma.innerHTML = 'Debe guardar para registrar la firma';	

    }
});

//===========================================================================//

var wrapper2 = document.getElementById("signature-pad-2"),
    clearButtonFirmaTecnico = wrapper2.querySelector("[name=clearTecnico]"),
    saveButtonFirmaTecnico = wrapper2.querySelector("[name=saveTecnico]"),
    canvas2 = wrapper2.querySelector("canvas"),
    signaturePad2;
	
function resizeCanvas2() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
	
	//alert(canvas2.width + ' - ' + canvas2.offsetWidth + ' - ' + canvas2.height + ' - ' + canvas2.offsetHeight + ' - ' + ratio );
    canvas2.width = canvas2.width * ratio;  //canvas2.offsetWidth * ratio;
	
    canvas2.height = canvas2.height * (ratio * 2.5); //canvas2.offsetHeight * ratio;
	
    canvas2.getContext("2d").scale(ratio, ratio);	
}	

window.onresize = resizeCanvas2;
resizeCanvas2();

signaturePad2 = new SignaturePad(canvas2);

clearButtonFirmaTecnico.addEventListener("click", function (event) {
    signaturePad2.clear();
});

saveButtonFirmaTecnico.addEventListener("click", function (event) {
	var firma, mjeFirma, advertenciaFirma = null;

		firma = document.getElementById("firma_tecnico");
		mjeFirma = document.getElementById("mjeFirmaTecnico");				
		advertenciaFirma = document.getElementById("advertenciaFirmaTecnico");	

    if (signaturePad2.isEmpty()) {
        alert("Debe ingresar la firma!");
    } else {
		firma.value = signaturePad2.toDataURL();		
		
		mjeFirma.innerHTML = 'Firma del técnico generada correctamente';	
		advertenciaFirma.innerHTML = 'Debe guardar para registrar la firma';	

    }
});

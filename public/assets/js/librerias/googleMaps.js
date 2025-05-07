$(document).ready(function () {
    google.maps.event.addDomListener(window, 'load', initialize);

    $('#autocomplete').on('change', function(){
        $('#latitude').val('');
        $('#longitude').val('');        
    });

    $('#autocomplete').on('focusout', function(){
        
        setTimeout(() => {
            lat = $('#latitude').val();       
            long = $('#longitude').val();       


        if(lat == '') 
        {
            $('#autocomplete').focus();            
            showToast('Seleccione ubicación ofrecida por Google Maps', '', 'error');
        }
        else 
        showToast('Ubicación cargada correctamente.' + '<br>' + 'Latitud: ' +lat + '<br>' +  ' Longitud: ' + long , '', 'success');
        }, 500);
        
    });

});



function initialize() {
    var input = document.getElementById('autocomplete');
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function () {

        var place = autocomplete.getPlace();
        console.log(place);
        $('#latitude').val(place.geometry['location'].lat());
        $('#longitude').val(place.geometry['location'].lng());

        /* otros valores */
        let address1 = "";
        let postcode = "";
        for (const component of place.address_components) {
            // @ts-ignore remove once typings fixed
            const componentType = component.types[0];            

            switch (componentType) {
                case "street_number": {
                    address1 = `${component.long_name} ${address1}`;
                    console.log(address1);
                    break;


                }

                case "route": {
                    address1 += component.short_name;
                    console.log(address1);
                    break;
                }

                case "postal_code": {
                    postcode = `${component.long_name}${postcode}`;
                    console.log(postcode);
                    break;
                }

                case "postal_code_suffix": {
                    postcode = `${postcode}-${component.long_name}`;
                    console.log(postcode);

                    break;
                }
                case "locality":
                    localidad = component.long_name;
                    console.log(localidad);

                    break;
                case "administrative_area_level_1": {
                    admin_area = component.short_name;
                    console.log(admin_area);

                    break;
                }
                case "country":
                    pais = component.long_name;
                    console.log(pais);

                    break;
            }
        }

    });
}

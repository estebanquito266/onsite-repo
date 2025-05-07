function indicadoresObras() {


    getObrasPorMes().then(detalle => { //en public/assets/js/querys.js

        const categorias = [];
        const valores = [];
        total = 0;
        for (let key in detalle) {
            categorias.push(key);
            valores.push(detalle[key]);
            total += parseInt(detalle[key]);
        };

        destino = "#chart";//div id al que se inserta
        promedio = parseFloat(total / Object.keys(detalle).length).toFixed(2);
        tamaño = 350;

        makeArea('obras', valores, categorias, destino, tamaño); //en public/assets/js/makeAreaGraphics.js


        $('#total_obras_id').html('');
        $('#total_obras_id').html(promedio);

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

    getObrasPorMes().then(detalle => {

        const categorias = [];
        const valores = [];
        total = 0;
        for (let key in detalle) {
            categorias.push(key);
            total += parseInt(detalle[key]);
            valores.push(total);
        };

        destino = "#chart1";//div id al que se inserta
        makeArea('obras', valores, categorias, destino, 350);

        $('#total_obras_id_acumulado').html('');
        $('#total_obras_id_acumulado').html(total);

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

    getObrasSinObservaciones().then(detalle => {
        console.log(detalle);

        const categorias = [];
        const valores = [];
        total = 0;

        for (let key in detalle) {


            if (key != 'total_obras') {
                categorias.push(key);
                value = parseInt(detalle[key]);
                valores.push(value);
            }

        };

        destino = "#grafico_obras_sin_observaciones";//div id al que se inserta

        makeDonut(valores, categorias, destino, 150); //en public/assets/js/makeAreaGraphics.js


        $('#total_obras_sin_observaciones').html('');
        $('#total_obras_sin_observaciones').html(detalle.obras_sin_observaciones);
        $('#porcentaje_sin_observaciones').html(parseFloat((detalle.obras_sin_observaciones / detalle.total_obras) * 100).toFixed(2) + '%');


        destino = "#grafico_obras_con_observaciones";//div id al que se inserta
        makeDonut(valores, categorias, destino, 150); //en public/assets/js/makeAreaGraphics.js

        $('#total_obras_con_observaciones').html('');
        $('#total_obras_con_observaciones').html(detalle.obras_con_observaciones);
        $('#porcentaje_con_observaciones').html(parseFloat((detalle.obras_con_observaciones / detalle.total_obras) * 100).toFixed(2) + '%');



    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

    getPromedioCoordinadasCerradas().then(data => {
        console.log(data);

        $('#promedio_coordinadas').html('');
        $('#promedio_coordinadas').html(parseFloat(data.promedio_coordinadas).toFixed(0));

        $('#promedio_cerradas').html('');
        $('#promedio_cerradas').html(parseFloat(data.promedio_cerradas).toFixed(0));

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}




function visitasPorObra() {

    getVisitasPorObra().then(data => {

        let dataset = [];

        for (let index = 0; index < data.length; index++) {

            /* button = makeButton('button', 'sistema_button', data[index].id, data[index].nombre, 'btn btn-success sistema_button', 'Ver'); */

            let reparaciones = 0;
            for (let j = 0; j < data[index].sistema_onsite.length; j++) {
                reparaciones += data[index].sistema_onsite[j].reparacion_onsite.length
            }

            dataset.push([
                data[index].id,
                data[index].nombre,

                reparaciones
            ]);

        };

        columns =
            [
                { title: "id" },
                { title: "nombre" },
                { title: "visitas" },
            ]
            ;

        destino = '#table_indicadores_obras';

        
            completeDataTables(dataset, columns, destino);
       


    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}

function visitasPorTecnico() {

    getVisitasPorTecnico().then(data => {

        let dataset = [];

        for (let index = 0; index < data.length; index++) {

            /* button = makeButton('button', 'sistema_button', data[index].id, data[index].nombre, 'btn btn-success sistema_button', 'Ver'); */

            dataset.push([
                data[index].idTecnico,
                data[index].Tecnico,
                data[index].total,

            ]);

        };

        columns =
            [
                { title: "id" },
                { title: "Tecnico" },
                { title: "Visitas" },


            ]
            ;

        destino = '#table_indicadores_obras';

      
            completeDataTables(dataset, columns, destino);
      

    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}


function resultadosPorTecnico() {

    getResultadosReparacionPorTecnico().then(data => {
       
        let dataset = [];

        for (let index = 0; index < data.length; index++) {

            /* button = makeButton('button', 'sistema_button', data[index].id, data[index].nombre, 'btn btn-success sistema_button', 'Ver'); */

            dataset.push([
                data[index].Tecnico,
                data[index].cantidad,

            ]);

        };

        columns =
            [
                { title: "Tecnico" },
                { title: "Cant. Obras Obs/Rech." },
            ]
            ;

        destino = '#table_indicadores_obras';        

        completeDataTables(dataset, columns, destino);



    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}

function resultadosPorEmpresa() {

    getResultadosReparacionPorEmpresaInstaladora().then(data => {
        console.log(data);

        let dataset = [];

        for (let index = 0; index < data.length; index++) {

            /* button = makeButton('button', 'sistema_button', data[index].id, data[index].nombre, 'btn btn-success sistema_button', 'Ver'); */

            dataset.push([
                data[index].EmpInsta,
                data[index].cantidad,

            ]);

        };

        columns =
            [
                { title: "Empresa" },
                { title: "Cant. Obras Obs/Rech." },
            ]
            ;

        destino = '#table_indicadores_obras';

        console.log(dataset);


        completeDataTables(dataset, columns, destino);



    }).catch(error => {
        console.log('Error al procesar la petición. TRACE: ');
        console.log(error.responseJSON.trace);
        showToast('ERROR, vea la consola para rastrear la causa', '', 'error');
    });

}


















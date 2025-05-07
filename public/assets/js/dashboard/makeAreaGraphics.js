function makeArea(name, data, categories, destino, size) {
        /* ************** chart  */
        var options = {
            chart: {
                height: size,
                type: 'area'
            },
            series: [{
                name: name,
                data: data
            },
        ],
            stroke: {
                curve: 'smooth'
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: categories
            }
        }
    
        var chart = new ApexCharts(document.querySelector(destino), options);
    
        chart.render();
        /* ********************** */
}


function makeDonut(data, categories, destino, size) {
    /* ************** chart  */
    var options = {
        chart: {
            height: size,
            type: 'donut'
        },
        /* series: [{
            name: name,
            data: data
        }], */
        
        series: data,
        labels: categories,
        
        dataLabels: {
            enabled: true,
            /* formatter: function (val) {
                return val + "%"
              }, */
        },
      
    }

    var chart = new ApexCharts(document.querySelector(destino), options);

    chart.render();
    /* ********************** */
}
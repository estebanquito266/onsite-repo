// Forms Datepicker

$( document ).ready(function() {

    // Datepicker

    $('[data-toggle="datepicker"]').datepicker({
		date: moment(),
		format: "yyyy-mm-dd",
		language: 'es-ES'		
	});
	
    $('[data-toggle="datepicker-year"]').datepicker({
        startView: 2
    });

    $('[data-toggle="datepicker-month"]').datepicker({
        startView: 1
    });

    $('[data-toggle="datepicker-inline"]').datepicker({
        inline: true
    });
	
	
    $('[data-toggle="datepicker-icon"]').datepicker({
        trigger: '.datepicker-trigger',
		date: moment(),
		format: "yyyy-mm-dd",
		language: 'es-ES'		
    });

    $('[data-toggle="datepicker-button"]').datepicker({
        trigger: '.datepicker-trigger-btn'
    });

    // Daterangepicker

    $('input[name="daterange"]').daterangepicker();

    $('input[name="datetimes"]').daterangepicker({
        timePicker: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        locale: {
            format: 'M/DD hh:mm A'
        }
    });

    $('input[name="birthday"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),10)
    }, function(start, end, label) {
        var years = moment().diff(start, 'years');
        alert("You are " + years + " years old!");
    });


    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        "opens": "right",
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    $('input[name="daterange-centered"]').daterangepicker({
        "timePicker": true,
        "buttonClasses": "btn btn-success",
        "cancelClass": "btn-link bg-transparent rm-border text-danger",
        "opens": "center",
        "drops": "up",
        "startDate": "12/12/2018",
        "endDate": "12/18/2018",
    });
	
    // Datetimepicker
	var opts = {
		locale: 'es',
		format: 'YYYY-MM-DD HH:mm:ss',
		icons: {
                time: 'fa fa-clock',
                date: 'fa fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-calendar-check-o',
                clear: 'fa fa-trash',
                close: 'fa fa-times'
            }
	};
	
    $('#fecha_cerrado').datetimepicker(
		opts
	);	
	
    $('#fecha_vencimiento').datetimepicker(
		opts
	);		
	
    $('#fecha').datetimepicker(
		opts
	);		

});
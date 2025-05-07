// Datatables

$( document ).ready(function() {

    setTimeout(function () {

        $('#example').DataTable({
            paging:   false,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example0').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example1').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example2').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example3').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example4').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example5').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example6').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example7').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example8').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });

        $('#example9').DataTable({
            paging: true,
            pageLength: 5,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "desc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });


        $('#exampleAsc').DataTable({
            paging:   false,
			responsive: true,
			iDisplayLength: 100,
			order: [[ 0, "asc" ]],
			language: {
				"url": "/assets/js/scripts-init/tables-Spanish.json"
			}
        });	
		
		
        /*
        $('#example2').DataTable({
            scrollY:        '292px',
            scrollCollapse: true,
            paging:         false,
            "searching": false,
            "info": false
        });
        */
		
	

    }, 2000);

});
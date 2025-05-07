

$(document).ready(function() {
	
	$('#tabla').DataTable({
		paging:   true,
		responsive: true,
		
		colReorder: true,
		
		scrollX: true,
		
		lengthMenu: [[10, 20, 50, 100, 200, -1], [10, 20, 50, 100, 200, "All"]],
		language: {
			"url": "/js/Spanish.json"
		},
		
		
		
		order: [[ 0, "desc" ]]
	});
	
});

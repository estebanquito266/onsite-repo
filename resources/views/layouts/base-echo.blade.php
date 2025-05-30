
<script  src="/assets/js/pusher.js"></script>
<script type="module">
  

    
  var userid = '<?php echo auth()->user()->id; ?>';
  const channel = 'private-channel-process-bar.'+userid;
  window.Echo.private(channel)
    .listen('NewNoty', (e) => {
        //console.log(e.data);
        existsNoty();
  });


		

			
</script>

<script>
		function showGranOverlay() {
			document.getElementById('granoverlay').style.display = 'flex';

		}

		function hideGranOverlay() {
			document.getElementById('granoverlay').style.display = 'none';
		}

		hideGranOverlay();

		


		function downloadXLS(idForm, classHide = "") {
			var form = document.getElementById(idForm);

			var url = form.action;
			var method = form.method;

			var formData = new FormData(form);

			fetch(url, {
						method: method,
						body: formData
					})
				.then(response => {
					hideGranOverlay();
						if (!response.ok) {
							throw new Error('Error en la solicitud');
						}

						var filename = 'reporte.xls'; 
						var contentDisposition = response.headers.get('Content-Disposition');
						if (contentDisposition) {
							var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
							var matches = filenameRegex.exec(contentDisposition);
							if (matches != null && matches[1]) {
								filename = matches[1].replace(/['"]/g, '');
							}
						}

						return response.blob().then(blob => {
							var urlBlob = window.URL.createObjectURL(blob);

							var a = document.createElement('a');
							a.href = urlBlob;
							a.download = filename; 
							document.body.appendChild(a);
							a.click(); 
							document.body.removeChild(a); 
							window.URL.revokeObjectURL(urlBlob); 

							if(classHide.trim() !== ''){
								var elementos = document.getElementsByClassName(classHide.trim());
									for (var i = 0; i < elementos.length; i++) {
										elementos[i].style.display = 'none'; 
									}

									checkNewNotifications();
								}
						});

						
				})
			.catch(error => {
				console.error('Error al enviar el formulario:', error);
				hideGranOverlay();
			});
		}

		function downloadXLSByURL(url, classHide = "") {

			showGranOverlay();

			var dataForm = new FormData();
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

			dataForm.append('_token', CSRF_TOKEN);

			fetch(url, {
						method: 'POST',
						body: dataForm,
					})
				.then(response => {
					hideGranOverlay();
						if (!response.ok) {
							throw new Error('Error en la solicitud');
						}

						var filename = 'reporte.xls'; 
						var contentDisposition = response.headers.get('Content-Disposition');
						if (contentDisposition) {
							var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
							var matches = filenameRegex.exec(contentDisposition);
							if (matches != null && matches[1]) {
								filename = matches[1].replace(/['"]/g, '');
							}
						}

						return response.blob().then(blob => {
							var urlBlob = window.URL.createObjectURL(blob);

							var a = document.createElement('a');
							a.href = urlBlob;
							a.download = filename; 
							document.body.appendChild(a);
							a.click(); 
							document.body.removeChild(a); 
							window.URL.revokeObjectURL(urlBlob); 

							if(classHide.trim() !== ''){
								var elementos = document.getElementsByClassName(classHide.trim());
									for (var i = 0; i < elementos.length; i++) {
										elementos[i].style.display = 'none'; 
									}
								}

								checkNewNotifications();
						});

						
				})
			.catch(error => {
				console.error('Error al enviar el formulario:', error);
				hideGranOverlay();
			});
		}

		function checkNewNotifications() {
				
				fetch('/notificationshtml', {
					method: 'GET',
					headers: {
						'Content-Type': 'application/json',
					}
				})
				.then(response => response.json())
				.then(data => {
					const notificationContainer = document.getElementById('notification-container');
					notificationContainer.innerHTML = data.html; 
				})
				.catch(error => {
					console.error('Error fetching notifications HTML:', error);
				});
			}


			
  		
		function existsNoty() {
			fetch('/existsNoty') 
				.then(response => response.json())
				.then(data => {
					console.log(data); 
					if (data.message === 'yes') {
						
						
						const notificaciones = data.notificaciones;
						var classHide='';
						notificaciones.forEach(notificacion => {
						
							classHide = 'downloadnoti'+notificacion.data.form_id;
							if(classHide.trim() !== ''){
								var elementos = document.getElementsByClassName(classHide.trim());
									for (var i = 0; i < elementos.length; i++) {
										elementos[i].style.visibility = 'visible'; 
									}
								}

								checkNewNotifications();
						});
						
					} 
				})
				.catch(error => {
					console.error('Error:', error);
				});
			}
				


			document.addEventListener("DOMContentLoaded", function(event) {
				$(document).ready(function() {

						$('.clickoverlay').click(function() {
							showGranOverlay();
						});

						$(document).on('show.bs.modal', '.modal', function () {
							//console.log('Se está abriendo un modal');
							hideGranOverlay();
						});

						$(document).ajaxComplete(function(event, xhr, settings) {
							//console.log('Se completo una solicitud ajax');
							hideGranOverlay();
						});


						});
			});

	</script>


<style>

</style>

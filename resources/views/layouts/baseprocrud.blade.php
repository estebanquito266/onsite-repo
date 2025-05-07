<!doctype html>
<html lang="es-AR">

<head>
    @include('layouts.basepro-head')

</head>
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    
	@include('layouts.basepro-body-header')
	
    @include('layouts.basepro-body-theme')
	
    <div class="app-main">
            @include('layouts.basepro-body-sidebar')
			
			<div class="app-main__outer">
                <div class="app-main__inner">
					
					@include('alerts.warnings')
					@include('alerts.success')
					@include('alerts.request')	
					@include('alerts.errors')
					
					
					@yield('content')

                </div>
				
                @include('layouts.basepro-body-footer')
				
			</div>
    </div>
</div>

@include('layouts.basepro-body-drawer')

<!--SCRIPTS INCLUDES-->

<!--CORE-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
<script src="/assets/js/scripts-init/app.js"></script>
<script src="/assets/js/scripts-init/demo.js"></script>

<!--COMPONENTS-->

<!--Perfect Scrollbar -->
<script src="/assets/js/vendors/scrollbar.js"></script>
<script src="/assets/js/scripts-init/scrollbar.js"></script>



<!--FORMS-->
<!--BlockUI -->
<script src="/assets/js/vendors/blockui.js"></script>
<script src="/assets/js/scripts-init/blockui.js"></script>

<!--Datepickers-->
<script src="/assets/js/vendors/form-components/datepicker.js"></script>
<script src="/assets/js/vendors/form-components/daterangepicker.js"></script>
<script src="/assets/js/vendors/form-components/moment.js"></script>

<script src="/assets/js/scripts-init/form-components/datepicker.js"></script>
<script src="/assets/js/scripts-init/form-components/datepicker.es-ES.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="/assets/js/scripts-init/form-components/es.js"></script>

<!--Form Validation-->
<script src="/assets/js/vendors/form-components/form-validation.js"></script>
<script src="/assets/js/scripts-init/form-components/form-validation.js"></script>

<!--Input Mask-->
<script src="/assets/js/vendors/form-components/input-mask.js"></script>
<script src="/assets/js/scripts-init/form-components/input-mask.js"></script>

<!--Toggle Switch -->
<script src="/assets/js/vendors/form-components/toggle-switch.js"></script>

<!--TABLES -->
<!--DataTables-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs4@1.10.19/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js" crossorigin="anonymous"></script>

<!--Bootstrap Tables-->
<script src="/assets/js/vendors/tables.js"></script>

<!--Tables Init-->
<script src="/assets/js/scripts-init/tables.js"></script>

<!--Multiselect-->
<script src="/assets/js/vendors/form-components/bootstrap-multiselect.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="/assets/js/scripts-init/form-components/input-select.js"></script>

<!--Toastr-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
<script src="/assets/js/scripts-init/toastr.js"></script>

<!--SweetAlert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="/assets/js/scripts-init/sweet-alerts.js"></script>

<!-- Personalized -->
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/index-onsite.js') !!}"></script>

@section('scripts')
@show

</body>
</html>

@section('modals')
@include('_onsite.respuestosonsite.modalExportador') 
@show

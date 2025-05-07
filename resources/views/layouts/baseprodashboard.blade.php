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

<!--CHARTS-->

<!--Apex Charts-->
<script src="/assets/js/vendors/charts/apex-charts.js"></script>

<script src="/assets/js/scripts-init/charts/apex-charts.js"></script>
<script src="/assets/js/scripts-init/charts/apex-series.js"></script>

<!--Sparklines-->
<script src="/assets/js/vendors/charts/charts-sparklines.js"></script>
<script src="/assets/js/scripts-init/charts/charts-sparklines.js"></script>

<!--Chart.js-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script src="/assets/js/scripts-init/charts/chartsjs-utils.js"></script>
<script src="/assets/js/scripts-init/charts/chartjs.js"></script>



<!--Slick Carousel -->
<script src="/assets/js/vendors/carousel-slider.js"></script>
<script src="/assets/js/scripts-init/carousel-slider.js"></script>


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

<!--Toastr-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
<script src="/assets/js/scripts-init/toastr.js"></script>

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

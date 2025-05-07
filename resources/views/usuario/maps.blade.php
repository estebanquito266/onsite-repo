@extends('layouts.baseprocrud')
@section('content')



<div class="main-card mb-3 card imagen_despiece">
	<div class="card-header card-header-tab  ">
		<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
			<i class="header-icon pe-7s-global mr-3 text-muted opacity-6"> </i>
			Mapeo de Usuarios
		</div>
		<div class="btn-actions-pane-right actions-icon-btn">
			<div class="btn-group dropdown">
				<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
					<i class="pe-7s-menu btn-icon-wrapper"></i>
				</button>
				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
					<button type="button" tabindex="0" class="dropdown-item" id="showtoast">
						<i class="dropdown-icon lnr-inbox"> </i><span>Menus</span>
					</button>
					<button type="button" tabindex="0" class="dropdown-item">
						<i class="dropdown-icon lnr-file-empty"> </i><span id="pruebas">Settings</span>
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">
		<h5 class="card-title"></h5>
		<div class="col-12">
			<div class="text-center">


				<div style="width: 100%; height: 600px;">
					{!! Mapper::render() !!}
				</div>

			</div>


		</div>
	</div>
</div>









@endsection

@section('scripts')

<script type="text/javascript" src="{!! asset('/assets/js/librerias/showMaps.js') !!}"></script>

@endsection